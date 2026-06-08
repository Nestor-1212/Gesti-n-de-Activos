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
        Schema::create('software_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->string('software_name');
            $table->string('license_key')->nullable();
            $table->string('version')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('vendor')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_licenses');
    }
};
