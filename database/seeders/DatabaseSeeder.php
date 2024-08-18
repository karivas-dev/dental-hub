<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Clinic;
use App\Models\Role;
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
    }
}
