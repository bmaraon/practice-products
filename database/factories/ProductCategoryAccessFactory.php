<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\ProductCategoryAccess;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategoryAccess>
 */
class ProductCategoryAccessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategoryAccess::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productCategoryIds = ProductCategory::all()->pluck('id');

        return [
            'product_category_id' => $this->faker->randomElement($productCategoryIds),
            'min_user_age'        => 18,
            'max_user_age'        => 30,
        ];
    }
}
