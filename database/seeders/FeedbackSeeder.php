<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Evaluation;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $evaluations = Evaluation::all();
        $penilaiUsers = User::role('penilai')->get();
        $validatorUsers = User::role('validator')->get();

        if ($evaluations->isEmpty() || ($penilaiUsers->isEmpty() && $validatorUsers->isEmpty())) {
            $this->command->warn('Skipping FeedbackSeeder: Not enough Evaluations or Feedback Givers found.');
            return;
        }

        foreach ($evaluations as $evaluation) {
            // Feedback dari penilai (saat mengisi evaluasi)
            if (rand(0, 1)) { // 50% kemungkinan ada feedback dari penilai
                Feedback::firstOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'given_by_id' => $evaluation->evaluated_by_id, // Feedback dari penilai itu sendiri
                    ],
                    ['content' => 'Feedback dari penilai: ' . fake()->sentence(5)]
                );
            }

            // Feedback dari validator (jika evaluasi sudah submitted/needs_revision)
            if (in_array($evaluation->status, ['submitted', 'needs_revision']) && !$validatorUsers->isEmpty()) {
                 if (rand(0, 1)) { // 50% kemungkinan ada feedback dari validator
                    $randomValidator = $validatorUsers->random();
                    Feedback::firstOrCreate(
                        [
                            'evaluation_id' => $evaluation->id,
                            'given_by_id' => $randomValidator->id,
                            'content' => 'Catatan revisi dari validator: ' . fake()->sentence(8)
                        ]
                    );
                 }
            }
        }
        $this->command->info('Feedback seeded.');
    }
}