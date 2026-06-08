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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('collaborator_id');
            $table->unsignedBigInteger('assigned_by');
            $table->date('assigned_at');
            $table->date('expected_return')->nullable();
            $table->date('returned_at')->nullable();
            $table->string('reason')->nullable();
            $table->text('observations')->nullable();
            $table->text('return_observations')->nullable();
            $table->string('signature_path')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
