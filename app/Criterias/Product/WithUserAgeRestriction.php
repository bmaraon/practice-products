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
    protected $categoryId;

    /**
     * Class Constructor
     *
     * @param $model
     * @param bool $includeWithoutAgeRestriction
     * @param int $userAge
     * @param int $minUserAge
     * @param int $maxUserAge
     * @param int $categoryId
     * @return void
     *
     */
    public function __construct($includeWithoutAgeRestriction, $userAge, $minUserAge, $maxUserAge, $categoryId)
    {
        $this->includeWithoutAgeRestriction = (int) $includeWithoutAgeRestriction;
        $this->minUserAge                   = (int) $minUserAge;
        $this->maxUserAge                   = (int) $maxUserAge;
        $this->categoryId                   = (int) $categoryId;
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
        $categoryId                   = $this->categoryId;
        $triggerUserAgeRestriction    = $this->userAge >= $this->minUserAge && $this->userAge <= $this->maxUserAge;
        $includeWithoutAgeRestriction = $this->includeWithoutAgeRestriction;

        return $model->leftJoin('product_category_access', 'product_category_access.product_category_id', '=', 'products.product_category_id')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('products.product_category_id', $categoryId);
            })
            ->where(function ($query) use ($triggerUserAgeRestriction, $includeWithoutAgeRestriction, $minUserAge, $maxUserAge, $categoryId) {
                $query->when($triggerUserAgeRestriction, function ($query) use ($includeWithoutAgeRestriction, $minUserAge, $maxUserAge, $categoryId) {
                    $query->where(function ($query) use ($minUserAge, $maxUserAge, $categoryId) {
                        $query->whereNotNull('product_category_access.id')
                            ->where('product_category_access.product_category_id', $categoryId)
                            ->whereRaw("product_category_access.min_user_age >= $minUserAge")
                            ->whereRaw("product_category_access.max_user_age <= $maxUserAge");
                    })->when($includeWithoutAgeRestriction, function ($query) use ($categoryId) {
                        $query->orWhere(function ($query) use ($categoryId) {
                            $query->whereNull('product_category_access.id')
                                ->where('products.product_category_id', $categoryId);
                        });
                    });
                });
            })->select(DB::raw('products.*'));
    }
}
