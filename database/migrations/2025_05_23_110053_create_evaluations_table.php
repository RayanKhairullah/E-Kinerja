<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluated_by_id')->constrained('users')->onDelete('cascade'); // Penilai
            $table->foreignId('evaluation_category_id')->constrained('evaluation_categories')->onDelete('cascade');
            $table->string('jabatan')->nullable();
            $table->integer('score')->nullable(); // Skor penilaian, misalnya 1-5
            $table->text('notes')->nullable(); // Catatan tambahan dari penilai
            $table->enum('status', ['pending', 'validated', 'needs_revision'])->default('pending'); // Status evaluasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};