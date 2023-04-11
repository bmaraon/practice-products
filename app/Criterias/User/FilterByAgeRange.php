<?php

namespace App\Criterias\User;

use App\Contracts\CriteriaInterface;

class FilterByAgeRange implements CriteriaInterface
{
    /**
     * @var int
     *
     */
    protected $minAge;

    /**
     * @var int
     *
     */
    protected $maxAge;

    /**
     * Class Constructor
     *
     * @param $model
     * @param int $age
     * @return void
     *
     */
    public function __construct($minAge, $maxAge)
    {
        $this->minAge = (int) $minAge;
        $this->maxAge = (int) $maxAge;
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
        return $model->whereBetween('age', [$this->minAge, $this->maxAge]);
    }
}
