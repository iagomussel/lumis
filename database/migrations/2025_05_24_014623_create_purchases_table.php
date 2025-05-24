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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('user_id'); // usuário responsável
            
            // Tipo de documento
            $table->enum('type', ['quotation', 'purchase_order'])->default('quotation');
            
            // Status da compra
            $table->enum('status', [
                'draft', 'sent', 'confirmed', 'received', 
                'partially_received', 'cancelled'
            ])->default('draft');
            
            // Valores
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Condições comerciais
            $table->integer('payment_terms')->default(30); // prazo em dias
            $table->enum('payment_method', [
                'bank_transfer', 'bank_slip', 'check', 'pix'
            ])->nullable();
            
            // Datas importantes
            $table->date('delivery_date')->nullable();
            $table->date('quote_valid_until')->nullable();
            
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['type', 'status']);
            $table->index('purchase_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
