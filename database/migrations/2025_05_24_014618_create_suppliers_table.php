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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('state_registration')->nullable();
            $table->string('municipal_registration')->nullable();
            
            // Endereço
            $table->string('address')->nullable();
            $table->string('address_number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('Brasil');
            
            // Informações comerciais
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->string('contact_person')->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // avaliação de 0 a 10
            
            // Condições comerciais
            $table->integer('payment_terms')->default(30); // prazo de pagamento em dias
            $table->decimal('discount_percentage', 5, 2)->default(0);
            
            $table->timestamps();
            
            $table->index(['status', 'rating']);
            $table->index('cnpj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
