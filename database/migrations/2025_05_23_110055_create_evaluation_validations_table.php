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
        Schema::create('evaluation_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('validator_id')->constrained('users')->onDelete('cascade'); // Validator yang memvalidasi
            $table->enum('status', ['approved', 'rejected', 'revision_requested']);
            $table->text('notes')->nullable(); // Catatan dari validator
            $table->timestamps();

            $table->unique(['evaluation_id', 'validator_id']); // Satu validator hanya bisa memvalidasi satu evaluasi sekali (jika perlu)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_validations');
    }
};