<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Class Constructor
     *
     * @return void
     *
     */
    public function __construct()
    {
        parent::__construct(Product::class);
    }
}
