<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $store = $this->faker->words(2, true);

        return [
            'tenant_id' => 1, 
            'store_id' => 1, 
            'name' => $store, 
            'slug' => str()->of($store)->slug(), 
            'description' => $this->faker->words(2, true)
            //
        ];
    }
}
