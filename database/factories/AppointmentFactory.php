<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTime(),
            'details' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['Programada', 'Reagendada', 'Cancelada', 'Completada']),
            'amount' => $this->faker->randomFloat(2, 0, 99.99),
            'user_id' => User::factory(),
            'patient_id' => Patient::factory(),
            'branch_id' => Branch::factory(),
        ];
    }
}
