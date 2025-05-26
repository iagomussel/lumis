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
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Option Details
            $table->string('name'); // e.g., "Size", "Color", "Material"
            $table->string('display_name')->nullable(); // e.g., "Tamanho", "Cor", "Material"
            $table->integer('position')->default(0); // Order of options (Size first, then Color, etc.)
            
            // Option Type and Behavior
            $table->enum('type', ['select', 'color', 'image', 'text'])->default('select');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_visible')->default(true);
            
            // Display Settings
            $table->enum('display_style', ['dropdown', 'buttons', 'swatches', 'list'])->default('dropdown');
            $table->string('help_text')->nullable();
            
            // Validation Rules
            $table->json('validation_rules')->nullable(); // Custom validation rules
            $table->integer('min_selections')->default(1);
            $table->integer('max_selections')->default(1);
            
            // Metadata
            $table->json('metafields')->nullable(); // Custom fields
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'position']);
            $table->index(['product_id', 'is_active']);
            $table->unique(['product_id', 'name']); // Each product can have only one option with the same name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
    }
};
