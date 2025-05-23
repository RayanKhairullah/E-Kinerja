<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // Import Role model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan roles sudah ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $penilaiRole = Role::firstOrCreate(['name' => 'penilai']);
        $validatorRole = Role::firstOrCreate(['name' => 'validator']);

        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat di produksi
            ]
        );
        $admin->assignRole($adminRole);
        $this->command->info('Admin user created: admin@example.com (password: password)');


        // 2. Penilai (Evaluator) Users
        // Buat 3 penilai
        for ($i = 1; $i <= 3; $i++) {
            $penilai = User::firstOrCreate(
                ['email' => "penilai{$i}@example.com"],
                [
                    'name' => "Penilai {$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $penilai->assignRole($penilaiRole);
            $this->command->info("Penilai user created: penilai{$i}@example.com (password: password)");
        }

        // 3. Validator Users
        // Buat 2 validator
        for ($i = 1; $i <= 2; $i++) {
            $validator = User::firstOrCreate(
                ['email' => "validator{$i}@example.com"],
                [
                    'name' => "Validator {$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $validator->assignRole($validatorRole);
            $this->command->info("Validator user created: validator{$i}@example.com (password: password)");
        }

        // 4. Guru (Users to be evaluated) - ini adalah user biasa tanpa peran khusus
        // Buat 5 guru
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => "guru{$i}@example.com"],
                [
                    'name' => "Guru {$i}",
                    'password' => Hash::make('password'),
                ]
            );
             $this->command->info("Guru user created: guru{$i}@example.com (password: password)");
        }
    }
}