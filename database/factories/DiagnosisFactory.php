<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Models\Diagnosis;

class DiagnosisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Diagnosis::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'details' => $this->faker->text(),
            'appointment_id' => Appointment::factory(),
        ];
    }
}
