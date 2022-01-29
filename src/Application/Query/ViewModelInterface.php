<?php

namespace App\Application\Query;

interface ViewModelInterface
{
    public function normalize(): mixed;
}