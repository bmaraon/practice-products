<?php

namespace App\Criterias\Product;

use App\Contracts\CriteriaInterface;

class FilterByCategory implements CriteriaInterface
{
    /**
     * @var int
     *
     */
    protected $productCategoryId;

    /**
     * Class Constructor
     *
     * @param $model
     * @param int $productCategoryId
     * @return void
     *
     */
    public function __construct($productCategoryId)
    {
        $this->productCategoryId = (int) $productCategoryId;
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
        return $model->where('products.product_category_id', $this->productCategoryId);
    }
}
