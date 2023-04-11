<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = ProductCategory::all();

        foreach ($productCategories as $productCategory) {
            Product::factory()->create([
                'product_category_id' => $productCategory->id,
            ]);
        }
    }
}
