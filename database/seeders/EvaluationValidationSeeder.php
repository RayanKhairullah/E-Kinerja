<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Evaluation;
use App\Models\EvaluationValidation;
use Illuminate\Database\Seeder;

class EvaluationValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $evaluationsToValidate = Evaluation::whereIn('status', ['submitted', 'needs_revision'])->get();
        $validatorUsers = User::role('validator')->get();

        if ($evaluationsToValidate->isEmpty() || $validatorUsers->isEmpty()) {
            $this->command->warn('Skipping EvaluationValidationSeeder: Not enough Evaluations to validate or Validators found.');
            return;
        }

        foreach ($evaluationsToValidate as $evaluation) {
            $randomValidator = $validatorUsers->random(); // Ambil validator secara acak

            $statusOptions = ['approved', 'rejected', 'revision_requested'];
            $randomStatus = $statusOptions[array_rand($statusOptions)];

            // Pastikan hanya ada satu validasi per evaluasi per validator
            $validation = EvaluationValidation::firstOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'validator_id' => $randomValidator->id,
                ],
                [
                    'status' => $randomStatus,
                    'notes' => ($randomStatus == 'revision_requested' ? 'Perlu revisi pada bagian ini: ' : 'Validasi OK. ') . fake()->sentence(5),
                ]
            );

            // Update status evaluasi berdasarkan validasi
            if ($validation->wasRecentlyCreated || $validation->wasChanged('status')) {
                 if ($validation->status === 'approved') {
                    $evaluation->update(['status' => 'validated']);
                } elseif ($validation->status === 'revision_requested') {
                    $evaluation->update(['status' => 'needs_revision']);
                }
            }
        }
        $this->command->info('Evaluation validations seeded.');
    }
}