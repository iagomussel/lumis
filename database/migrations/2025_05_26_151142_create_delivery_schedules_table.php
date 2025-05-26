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
        Schema::create('delivery_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('tracking_code')->unique();
            
            // Delivery Information
            $table->date('scheduled_date');
            $table->time('scheduled_time_start')->nullable();
            $table->time('scheduled_time_end')->nullable();
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state');
            $table->string('delivery_zip_code');
            $table->text('delivery_notes')->nullable();
            
            // Financial Information
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2);
            
            // Status and Tracking
            $table->enum('status', ['scheduled', 'confirmed', 'in_transit', 'delivered', 'cancelled'])->default('scheduled');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Delivery Personnel
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('vehicle_info')->nullable();
            
            // Additional Information
            $table->text('special_instructions')->nullable();
            $table->boolean('requires_signature')->default(false);
            $table->string('signature_image_path')->nullable();
            $table->text('delivery_proof_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['scheduled_date', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('tracking_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_schedules');
    }
};
