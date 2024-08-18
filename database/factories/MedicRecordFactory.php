<?php

namespace Database\Factories;

use App\Models\MedicRecord;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'details' => $this->faker->sentence(),
            'treatment' => $this->faker->sentence(),
            'hereditary' => $this->faker->boolean(),
            'kinship' => $this->faker->randomElement(['Padre', 'Madre', "Hermano\/a", "Abuelo\/a", "Tio\/a", "Primo\/a", "Tatara-abuelo\/a"]),
            'system' => $this->faker->randomElement(['Respiratorio', 'Cardiovascular', 'Digestivo', 'Endocrino', 'Excretor', "Inmunol\u00f3gico", 'Muscular', 'Nervioso', 'Reproductor', "\u00d3seo", 'Circulatorio', "Linf\u00e1tico", 'Tegumentario']),
            'patient_id' => Patient::factory(),
        ];
    }
}
