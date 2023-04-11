<?php

namespace App\Contracts;

interface CriteriaInterface
{
    public function apply($model);
}
