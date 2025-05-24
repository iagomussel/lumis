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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(1000);
            $table->enum('status', ['active', 'inactive', 'discontinued']);
            $table->boolean('is_customizable')->default(false);
            $table->json('custom_fields')->nullable(); // campos personalizados
            $table->json('images')->nullable(); // array de imagens
            $table->json('files')->nullable(); // array de arquivos anexos
            $table->decimal('weight', 8, 3)->nullable();
            $table->string('dimensions')->nullable(); // LxAxP
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->index(['status', 'featured']);
            $table->index(['sku', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
