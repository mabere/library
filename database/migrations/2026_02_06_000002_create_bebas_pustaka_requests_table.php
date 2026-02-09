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
        Schema::create('bebas_pustaka_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('letter_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->string('nim');
            $table->string('nama');
            $table->string('prodi');
            $table->enum('status', [
                'diajukan',
                'diverifikasi_staf',
                'ditolak_staf',
                'disetujui_kepala',
            ])->default('diajukan');
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('has_fine')->nullable();
            $table->string('fine_note')->nullable();
            $table->string('rejection_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebas_pustaka_requests');
    }
};
