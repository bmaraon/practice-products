<?php

namespace App\Criterias\User;

use App\Contracts\CriteriaInterface;

class FilterByAge implements CriteriaInterface
{
    /**
     * @var int
     *
     */
    protected $age;

    /**
     * Class Constructor
     *
     * @param $model
     * @param int $age
     * @return void
     *
     */
    public function __construct($age)
    {
        $this->age = (int) $age;
    }

    /**
     * Apply
     *
     * @param $model
     * @param mixed $filter
     * @return $model
     *
     */
    public function apply($model)
    {
        return $model->where('age', $this->age);
    }
}
