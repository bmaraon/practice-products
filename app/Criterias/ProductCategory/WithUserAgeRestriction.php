<?php

namespace App\Criterias\ProductCategory;

use App\Contracts\CriteriaInterface;
use Illuminate\Support\Facades\DB;

class WithUserAgeRestriction implements CriteriaInterface
{
    /**
     * @var int
     *
     * To indicate if request filtering can include product categories without age range filter
     *
     */
    protected $includeWithoutAgeRestriction;

    /**
     * @var int
     *
     * If product category have age range filter record
     * User age will be used as the filter for the access authorization
     *
     */
    protected $userAge;

    /**
     * @var int
     *
     */
    protected $minUserAge;

    /**
     * @var int
     *
     */
    protected $maxUserAge;

    /**
     * Class Constructor
     *
     * @param $model
     * @param bool $includeWithoutAgeRestriction
     * @param int $userAge
     * @return void
     *
     */
    public function __construct($includeWithoutAgeRestriction, $userAge, $minUserAge, $maxUserAge)
    {
        $this->includeWithoutAgeRestriction = (int) $includeWithoutAgeRestriction;
        $this->minUserAge                   = (int) $minUserAge;
        $this->maxUserAge                   = (int) $maxUserAge;
        $this->userAge                      = (int) $userAge;
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
        $minUserAge                   = $this->minUserAge;
        $maxUserAge                   = $this->maxUserAge;
        $triggerUserAgeRestriction    = $this->userAge >= $this->minUserAge && $this->userAge <= $this->maxUserAge;
        $includeWithoutAgeRestriction = $this->includeWithoutAgeRestriction;

        return $model->leftJoin('product_category_access', 'product_category_access.product_category_id', '=', 'product_categories.id')
            ->when($triggerUserAgeRestriction, function ($query) use ($minUserAge, $maxUserAge) {
                $query->where(function ($query) use ($minUserAge, $maxUserAge) {
                    $query->whereNotNull('product_category_access.id')
                        ->whereRaw("product_category_access.min_user_age >= $minUserAge")
                        ->whereRaw("product_category_access.max_user_age <= $maxUserAge");
                });
            })
            ->when($includeWithoutAgeRestriction, function ($query) {
                $query->orWhereNull('product_category_access.id');
            })
            ->select(DB::raw('product_categories.*'));
    }
}
