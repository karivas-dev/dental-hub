<?php

namespace Database\Seeders;

use App\Models\Tooth;
use Illuminate\Database\Seeder;

class ToothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tooths = [
            'Cordal Superior',
            'Segundo Molar de Adulto Superior',
            'Primer Molar de Adulto Superior',
            'Segundo Premolar Superior',
            'Primer Prmolar Superior',
            'Canino Superior',
            'Incisivo Lateral Superior',
            'Incisivo Central Superior',
            'Incisivo Central Superior',
            'Incisivo Lateral Superior',
            'Canino Superior',
            'Primer Premolar Superior',
            'Segundo Premolar Superior',
            'Primer Molar de Adulto Superior',
            'Segundo Molar de Adulto Superior',
            'Cordal Sueprior',
            'Cordal Inferior',
            'Segundo Molar de Adulto Inferior',
            'Primer Molar de Adulto Inferior',
            'Segundo Premolar Inferior',
            'Primer Premolar Inferior',
            'Canino Inferior',
            'Incisivo Lateral Inferior',
            'Incisivo Central Inferior',
            'Incisivo Central Inferior',
            'Incisivo Lateral Inferior',
            'Canino Inferior',
            'Primer Premolar Inferior',
            'Segundo Premolar Inferior',
            'Primer Molar de Adulto Inferior',
            'Segundo Molar de Adulto Inferior',
            'Cordal Inferior',
            'Segunda Molar Decidua Superior',
            'Primera Molar Decidua Superior',
            'Canino Deciduo Superior',
            'Incisivo Lateral Deciduo Superior',
            'Incisivo Central Deciduo Superior',
            'Incisivo Central Deciduo Superior',
            'Incisivo Lateral Deciduo Superior',
            'Canino Deciduo Superior',
            'Primera Molar Decidua Superior',
            'Segunda Molar Decidua Superior',
            'Segunda Molar Decidua Inferior',
            'Primera Molar Decidua Inferior',
            'Canino Deciduo Inferior',
            'Incisivo Lateral Deciduo Inferior',
            'Incisivo Central Deciduo Inferior',
            'Incisivo Central Deciduo Inferior',
            'Incisivo Lateral Deciduo Inferior',
            'Canino Deciduo Inferior',
            'Primera Molar Decidua Inferior',
            'Segunda Molar Decidua Inferior'
        ];

        Tooth::factory(count($tooths))->sequence(fn($sequence) => [
            'name' => $tooths[$sequence->index]
        ])->create();
    }
}
