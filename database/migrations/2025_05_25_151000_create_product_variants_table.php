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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique(); // SKU único da variante
            $table->string('name'); // Nome da variante (ex: "Camiseta Azul M")
            $table->json('option_values'); // array com valores das opções {color: "azul", size: "M"}
            $table->decimal('price_adjustment', 10, 2)->default(0); // ajuste no preço base
            $table->decimal('cost_adjustment', 10, 2)->default(0); // ajuste no custo base
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(0);
            $table->decimal('weight_adjustment', 8, 3)->default(0); // ajuste no peso base
            $table->boolean('active')->default(true);
            $table->string('barcode')->nullable();
            $table->json('images')->nullable(); // imagens específicas da variante
            $table->timestamps();
            
            $table->index(['product_id', 'active']);
            $table->index(['sku']);
            $table->index(['stock_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
}; 