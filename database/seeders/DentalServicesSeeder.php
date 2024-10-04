<?php

namespace Database\Seeders;

use App\Models\DentalService;
use Illuminate\Database\Seeder;

class DentalServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Ortodoncia',
            'Endodoncia',
            'Implantes',
            'Blanqueamiento',
            'Extracciones',
            'Limpieza',
            'Prótesis',
            'Odontopediatría',
            'Cirugía',
            'Periodoncia',
        ];

        DentalService::factory(count($services))->sequence(fn($sequence) => [
            'name' => $services[$sequence->index]
        ])->create();
    }
}
