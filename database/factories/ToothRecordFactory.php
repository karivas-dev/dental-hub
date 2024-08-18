<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Tooth;
use App\Models\ToothRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToothRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ToothRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'details' => $this->faker->sentence(),
            'patient_id' => Patient::factory(),
            'tooth_id' => Tooth::factory(),
        ];
    }
}
