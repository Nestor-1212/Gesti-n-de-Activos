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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->string('name');
            $table->enum('type', ['charger', 'case', 'headphones', 'sim', 'cable', 'keyboard', 'mouse', 'other'])->default('other');
            $table->string('brand')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('status', ['available', 'assigned', 'damaged', 'lost'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
