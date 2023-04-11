<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Repositories\BaseRepository;

class ProductCategoryRepository extends BaseRepository
{
    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct()
    {
        parent::__construct(ProductCategory::class);
    }
}
