<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting! Users harus dibuat pertama agar roles bisa diasosiasikan.
        // Categories juga harus sebelum Evaluations.
        $this->call([
            UserSeeder::class,
            EvaluationCategorySeeder::class,
            EvaluationSeeder::class,
            FeedbackSeeder::class,
            EvaluationValidationSeeder::class,
        ]);
    }
}