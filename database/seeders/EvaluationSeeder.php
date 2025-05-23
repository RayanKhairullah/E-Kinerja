<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Evaluation;
use App\Models\EvaluationCategory;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penilaiUsers = User::role('penilai')->get();
        $guruUsers = User::whereDoesntHave('roles')->get(); // Mengambil user yang tidak punya role (kita asumsikan ini guru)
        $categories = EvaluationCategory::all();

        if ($penilaiUsers->isEmpty() || $guruUsers->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Skipping EvaluationSeeder: Not enough Penilai, Guru, or Categories found.');
            return;
        }

        foreach ($penilaiUsers as $penilai) {
            foreach ($guruUsers as $guru) {
                foreach ($categories as $category) {
                    // Buat beberapa evaluasi dengan status berbeda
                    $statusOptions = ['draft', 'submitted', 'validated', 'needs_revision'];
                    $randomStatus = $statusOptions[array_rand($statusOptions)];

                    Evaluation::firstOrCreate(
                        [
                            'evaluated_by_id' => $penilai->id,
                            'evaluated_user_id' => $guru->id,
                            'evaluation_category_id' => $category->id,
                        ],
                        [
                            'score' => rand(1, 5),
                            'notes' => fake()->paragraph(2),
                            'status' => $randomStatus,
                        ]
                    );
                }
            }
        }
        $this->command->info('Evaluations seeded.');
    }
}