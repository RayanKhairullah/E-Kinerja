<?php

namespace Database\Seeders;

use App\Models\EvaluationCategory;
use Illuminate\Database\Seeder;

class EvaluationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Perencanaan Pembelajaran', 'description' => 'Aspek-aspek dalam perencanaan pembelajaran.'],
            ['name' => 'Pelaksanaan Pembelajaran', 'description' => 'Aspek-aspek selama pelaksanaan pembelajaran di kelas.'],
            ['name' => 'Evaluasi Pembelajaran', 'description' => 'Aspek-aspek dalam proses evaluasi hasil pembelajaran.'],
            ['name' => 'Pengembangan Diri', 'description' => 'Aspek-aspek yang berkaitan dengan pengembangan profesional guru.'],
            ['name' => 'Profesionalisme', 'description' => 'Aspek-aspek integritas dan etika profesi.'],
        ];

        foreach ($categories as $category) {
            EvaluationCategory::firstOrCreate(['name' => $category['name']], $category);
        }
        $this->command->info('Evaluation categories seeded.');
    }
}