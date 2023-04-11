<?php

namespace Database\Seeders;

use App\Models\ProductCategoryAccess;
use Illuminate\Database\Seeder;

class ProductCategoryAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategoryAccess::factory()->count(1)->create();
    }
}
