Feature:
    As a user
    I want to create a question

    Scenario: Creating a valid question
        When I create a question with title "My first question"
        Then I must have 1 question
        And its title must be "My first question"

    Scenario: Creating a wrong question with no title
        When I create a question with an empty title
        Then I must have 0 questions
        And I must have a NestedValidationException exception
