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
        Schema::create('production_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('design_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('status', ['pending', 'in_progress', 'quality_check', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->datetime('estimated_start')->nullable();
            $table->integer('estimated_duration')->nullable(); // minutes
            $table->datetime('actual_start')->nullable();
            $table->datetime('actual_end')->nullable();
            $table->integer('temperature')->nullable();
            $table->decimal('pressure', 8, 2)->nullable();
            $table->integer('time_seconds')->nullable();
            $table->enum('quality_check_status', ['pending', 'approved', 'rejected'])->nullable();
            $table->text('quality_check_notes')->nullable();
            $table->foreignId('quality_checked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('quality_checked_at')->nullable();
            $table->text('production_notes')->nullable();
            $table->integer('reject_quantity')->default(0);
            $table->text('reject_reason')->nullable();
            $table->json('materials_used')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['assigned_to']);
            $table->index(['order_id']);
            $table->index(['estimated_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_jobs');
    }
};
