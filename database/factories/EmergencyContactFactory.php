<?php

namespace Database\Factories;

use App\Models\EmergencyContact;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmergencyContact::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'cellphone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'kinship' => $this->faker->randomElement(['Padre', 'Madre', "Hermano\/a", "Abuelo\/a", "Tio\/a", "Primo\/a", "Sobrino\/a", "Amigo\/a", 'Otro']),
            'patient_id' => Patient::factory(),
        ];
    }
}
