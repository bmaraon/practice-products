<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

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
            'quantity'    => $this->faker->numberBetween($min = 0, $max = 100),
            'price'       => $this->faker->randomFloat($decimalPlaces = 2, $min = 1, $max = 100),
            'created_by'  => $this->faker->randomElement($userIDs),
            'updated_by'  => null,
            'deleted_by'  => null,
        ];
    }
}
