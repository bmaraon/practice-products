<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIDs = User::all()->pluck('id');

        return [
            'name'        => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'created_by'  => $this->faker->randomElement($userIDs),
            'updated_by'  => null,
            'deleted_by'  => null,
        ];
    }
}
