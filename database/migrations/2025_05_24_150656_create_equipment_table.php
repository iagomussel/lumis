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
            $table->string('name');
            $table->enum('type', ['printer', 'heat_press', 'cutter', 'laminator', 'other']);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_until')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive', 'broken'])->default('active');
            $table->string('location')->nullable();
            $table->integer('max_width')->nullable();
            $table->integer('max_height')->nullable();
            $table->integer('max_temperature')->nullable();
            $table->integer('min_temperature')->nullable();
            $table->decimal('max_pressure', 8, 2)->nullable();
            $table->json('capabilities')->nullable();
            $table->string('maintenance_schedule')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->date('next_maintenance')->nullable();
            $table->text('maintenance_notes')->nullable();
            $table->integer('usage_hours')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['type']);
            $table->index(['next_maintenance']);
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
