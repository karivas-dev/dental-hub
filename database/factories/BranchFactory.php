<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Municipality;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'main' => $this->faker->boolean(),
            'address' => $this->faker->sentence(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'clinic_id' => Clinic::factory(),
            'municipality_id' => Municipality::factory(),
        ];
    }
}
