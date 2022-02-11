ARGS = $(filter-out $@,$(MAKECMDGOALS))

CURRENT_UID := $(shell id -u)
CURRENT_GID := $(shell id -g)

DOCKER_COMPOSE  = docker-compose
DOCKER_EXEC     = $(DOCKER_COMPOSE) exec -u ${CURRENT_UID}:${CURRENT_UID}
DOCKER_RUN      = $(DOCKER_COMPOSE) run --rm -u ${CURRENT_UID}:${CURRENT_UID}

EXEC_PHP        = $(DOCKER_EXEC) php

SYMFONY         = $(EXEC_PHP) php bin/console
COMPOSER        = $(EXEC_PHP) composer
BEHAT           = $(EXEC_PHP) vendor/bin/behat
CS_FIXER        = $(EXEC_PHP) tools/php-cs-fixer/vendor/bin/php-cs-fixer
DEPTRAC        = $(EXEC_PHP) tools/deptrac/vendor/bin/deptrac

##
## Project
## -------
##

build:
	@$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

install: ## Install and start the project
install: build start db

reset: ## Stop and start a fresh install of the project
reset: kill install

start: ## Start the project
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop: ## Stop the project
	$(DOCKER_COMPOSE) down -v

clean: ## Stop the project and remove generated files
clean: kill
	rm -rf var vendor

.PHONY: build kill install reset start stop clean

##
## Utils
## -----
##

composer_req: ## Install package using composer
composer_req:
	@test "${ARGS}"
	$(COMPOSER) req ${ARGS}

composer_rem: ## Remove package using composer
composer_rem:
	@test "${ARGS}"
	$(COMPOSER) rem ${ARGS}

composer_install: ## Install package using composer
composer_install:
	$(COMPOSER) install

sf: ## Run Symfony command using bin/console
sf:
	@test "${ARGS}"
	$(SYMFONY) ${ARGS}

sf_entity: ## Make Doctrine entity
sf_entity:
	@test "${ARGS}"
	$(SYMFONY) make:entity \\App\\Infrastructure\\Entity\\${ARGS}

db: ## Reset the database and load fixtures
db: vendor
	@$(EXEC_PHP) php -r 'use Symfony\Component\Dotenv\Dotenv; echo "Wait database...\n"; set_time_limit(30); require __DIR__."/vendor/autoload.php"; (new Dotenv())->bootEnv(__DIR__."/.env"); $$u = parse_url($$_ENV["DATABASE_URL"]); for(;;) { if(@fsockopen($$u["host"].":".($$u["port"] ?? 5432))) { break; }}'
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration

migration: ## Generate a new doctrine migration
migration: vendor
	$(SYMFONY) make:migration

test_fake: ## Launch tests with in-memory adapters
test_fake:
	$(BEHAT) -p fake

test_real: ## Launch tests with in-memory adapters
test_real:
	-$(SYMFONY) doctrine:database:drop --if-exists --force --env=test_real
	-$(SYMFONY) doctrine:database:create --if-not-exists --env=test_real
	$(SYMFONY) doctrine:migrations:migrate --no-interaction --allow-no-migration --env=test_real
	$(BEHAT) -p real

cs_fixer: ## Run PHP CS Fixer
cs_fixer: vendor_cs_fixer
	$(CS_FIXER) fix src ${ARGS}

deptrac: ## Run Deptrac
deptrac: vendor_deptrac
	$(DEPTRAC) analyse --fail-on-uncovered --report-uncovered

.PHONY: composer_req composer_rem composer_install sf sf_entity db migration test_fake test_real cs_fixer deptrac

# rules based on files
composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: composer.lock
	$(COMPOSER) install --no-scripts

vendor_cs_fixer: tools/php-cs-fixer/composer.lock
	$(COMPOSER) install --no-scripts --working-dir=tools/php-cs-fixer

vendor_deptrac: tools/deptrac/composer.lock
	$(COMPOSER) install --no-scripts --working-dir=tools/deptrac



.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help



# To avoid ${ARGS} errors
%::
	@:
