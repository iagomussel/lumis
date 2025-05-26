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
        // Drop old product options system tables
        Schema::dropIfExists('product_option_assignments');
        Schema::dropIfExists('product_options');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate product_options table
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->enum('type', ['select', 'color', 'text', 'number'])->default('select');
            $table->json('available_values')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Recreate product_option_assignments table
        Schema::create('product_option_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_option_id')->constrained()->onDelete('cascade');
            $table->json('available_values')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['product_id', 'product_option_id']);
        });
    }
};
