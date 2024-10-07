<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Municipality;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'dui' => $this->faker->regexify('[A-Za-z0-9]{9}'),
            'email' => $this->faker->safeEmail(),
            'genre' => $this->faker->randomElement(['Femenino', 'Masculino']),
            'phone' => $this->faker->numerify('2########'),
            'cellphone' => $this->faker->numerify('7########'),
            'address' => $this->faker->sentence(),
            'occupation' => $this->faker->word(),
            'birthdate' => $this->faker->date(),
            'municipality_id' => Municipality::factory(),
            'clinic_id' => Clinic::factory(),
        ];
    }
}
