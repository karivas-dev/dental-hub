<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Role;
use App\Models\Tooth;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $admin = Role::create(['type' => 'Administración']);
        $doctor = Role::create(['type' => 'Doctor']);
        
        User::factory()->recycle($admin)->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'admin' => true,
        ]);

        $happyDentsMain = Branch::factory()->recycle(
            Clinic::create(['name' => 'Happy Dents'])
        )->create([
            'name' => 'Happy Dents - Edificios Morados',
            'main' => true,
            'address' => 'Condominio Medicentro La Esperanza, Edificio "C" Local #211',
            'phone' => '12345678',
            'email' => 'happydents@email.com'
        ]); 

        User::factory()->recycle($admin)->recycle($happyDentsMain)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->recycle($doctor)->recycle($happyDentsMain)->create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com'
        ]); 

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
