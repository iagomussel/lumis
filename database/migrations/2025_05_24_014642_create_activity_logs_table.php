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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // User information
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('user_email')->nullable(); // Store email for reference
            
            // Activity details
            $table->string('action'); // create, update, delete, login, logout, etc.
            $table->string('model_type')->nullable(); // Model class name
            $table->unsignedBigInteger('model_id')->nullable(); // Model ID
            $table->string('model_name')->nullable(); // Human readable model name
            
            // Context
            $table->text('description'); // Human readable description
            $table->json('old_values')->nullable(); // Old values for updates
            $table->json('new_values')->nullable(); // New values for updates
            $table->json('metadata')->nullable(); // Additional metadata
            
            // Request information
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            
            // Categorization
            $table->string('category')->default('general'); // auth, product, order, etc.
            $table->enum('severity', ['info', 'warning', 'error', 'critical'])->default('info');
            
            // Additional tracking
            $table->string('session_id')->nullable();
            $table->timestamp('performed_at');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'performed_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'category']);
            $table->index(['performed_at']);
            $table->index(['severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
