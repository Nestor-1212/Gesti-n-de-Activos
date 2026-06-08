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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique()->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->string('imei1')->nullable();
            $table->string('imei2')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('carrier')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('storage_capacity')->nullable();
            $table->string('ram')->nullable();
            $table->enum('type', ['smartphone', 'tablet', 'laptop', 'desktop', 'printer', 'router', 'switch', 'camera', 'other'])->default('smartphone');
            $table->enum('status', ['available', 'assigned', 'maintenance', 'damaged', 'lost', 'retired'])->default('available');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
