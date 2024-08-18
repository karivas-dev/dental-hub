<?php

namespace Database\Factories;

use App\Models\DentalService;
use Illuminate\Database\Eloquent\Factories\Factory;

class DentalServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DentalService::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
        ];
    }
}
