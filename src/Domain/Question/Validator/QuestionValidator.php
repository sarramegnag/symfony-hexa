<?php

namespace App\Domain\Question\Validator;

use App\Domain\Question\Model\Question;
use Respect\Validation\Validator as v;

class QuestionValidator
{
    public function validate(Question $question): void
    {
        $rules = v::attribute('title', v::stringType()->length(1, 255));

        $rules->assert($question);
    }
}
