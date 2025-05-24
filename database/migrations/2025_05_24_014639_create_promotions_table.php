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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique()->nullable(); // código da promoção
            
            // Tipo de promoção
            $table->enum('type', [
                'percentage_discount', 'fixed_discount', 'buy_one_get_one', 
                'free_shipping', 'bundle_deal'
            ]);
            
            // Valor do desconto
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            
            // Condições
            $table->decimal('minimum_order_value', 10, 2)->nullable();
            $table->integer('max_uses')->nullable(); // máximo de usos
            $table->integer('max_uses_per_customer')->default(1);
            $table->integer('current_uses')->default(0);
            
            // Vigência
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            
            // Status
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            
            // Aplicabilidade
            $table->json('applicable_products')->nullable(); // IDs dos produtos
            $table->json('applicable_categories')->nullable(); // IDs das categorias
            $table->json('applicable_customers')->nullable(); // IDs dos clientes
            
            // Email marketing
            $table->boolean('send_email')->default(false);
            $table->string('email_subject')->nullable();
            $table->text('email_template')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'starts_at', 'ends_at']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
