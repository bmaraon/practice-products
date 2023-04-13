<?php

namespace App\Criterias\Product;

use App\Contracts\CriteriaInterface;

class FilterByCategory implements CriteriaInterface
{
    /**
     * @var int
     *
     */
    protected $productCategoryIds;

    /**
     * Class Constructor
     *
     * @param $model
     * @param int $productCategoryIds
     * @return void
     *
     */
    public function __construct($productCategoryIds)
    {
        $this->productCategoryIds = (array) $productCategoryIds;
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
        return $model->whereIn('products.product_category_id', $this->productCategoryIds);
    }
}
