<?php

namespace App\Criterias\Product;

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
     * @var int
     *
     */
    protected $categoryIds;

    /**
     * Class Constructor
     *
     * @param $model
     * @param bool $includeWithoutAgeRestriction
     * @param int $userAge
     * @param int $minUserAge
     * @param int $maxUserAge
     * @param int $categoryIds
     * @return void
     *
     */
    public function __construct($includeWithoutAgeRestriction, $userAge, $minUserAge, $maxUserAge, $categoryIds)
    {
        $this->includeWithoutAgeRestriction = (int) $includeWithoutAgeRestriction;
        $this->categoryIds                  = (array) $categoryIds;
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
        $categoryIds                  = $this->categoryIds;
        $triggerUserAgeRestriction    = $this->userAge >= $this->minUserAge && $this->userAge <= $this->maxUserAge;
        $includeWithoutAgeRestriction = $this->includeWithoutAgeRestriction;

        return $model->leftJoin('product_category_access', 'product_category_access.product_category_id', '=', 'products.product_category_id')
            ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                $query->whereIn('products.product_category_id', $categoryIds);
            })
            ->where(function ($query) use ($triggerUserAgeRestriction, $includeWithoutAgeRestriction, $minUserAge, $maxUserAge, $categoryIds) {
                $query->when(!empty($triggerUserAgeRestriction), function ($query) use ($includeWithoutAgeRestriction, $minUserAge, $maxUserAge, $categoryIds) {
                    $query->where(function ($query) use ($minUserAge, $maxUserAge, $categoryIds) {
                        $query->whereNotNull('product_category_access.id')
                            ->whereRaw("product_category_access.min_user_age >= $minUserAge")
                            ->whereRaw("product_category_access.max_user_age <= $maxUserAge")
                            ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                                $query->whereIn('product_category_access.product_category_id', $categoryIds);
                            });
                    })->when(!empty($includeWithoutAgeRestriction), function ($query) use ($categoryIds) {
                        $query->orWhere(function ($query) use ($categoryIds) {
                            $query->whereNull('product_category_access.id')
                                ->when(!empty($categoryIds), function ($query) use ($categoryIds) {
                                    $query->whereIn('products.product_category_id', $categoryIds);
                                });
                        });
                    });
                });
            })->select(DB::raw('products.*'));
    }
}
