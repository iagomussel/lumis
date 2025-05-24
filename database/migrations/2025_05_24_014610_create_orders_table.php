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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id'); // vendedor/usuário responsável
            
            // Status do pedido
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'shipped', 
                'delivered', 'cancelled', 'refunded'
            ])->default('pending');
            
            // Valores
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Informações de entrega
            $table->string('shipping_address')->nullable();
            $table->string('shipping_number')->nullable();
            $table->string('shipping_complement')->nullable();
            $table->string('shipping_neighborhood')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip_code')->nullable();
            
            // Pagamento
            $table->enum('payment_method', [
                'credit_card', 'debit_card', 'bank_slip', 'pix', 
                'money', 'check', 'bank_transfer'
            ])->nullable();
            $table->enum('payment_status', [
                'pending', 'paid', 'partially_paid', 'cancelled', 'refunded'
            ])->default('pending');
            
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['status', 'payment_status']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
