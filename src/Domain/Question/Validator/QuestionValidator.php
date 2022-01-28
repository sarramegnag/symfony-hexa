<?php

namespace App\Domain\Question\Validator;

use Respect\Validation\Validator as v;

class QuestionValidator
{
    public function validate(array $data): void
    {
        $rules = v::key('title', v::stringType()->length(1, 255));

        $rules->assert($data);
    }
}