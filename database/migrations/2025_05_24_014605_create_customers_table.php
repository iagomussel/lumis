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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('document')->nullable(); // CPF/CNPJ
            $table->enum('document_type', ['cpf', 'cnpj'])->nullable();
            $table->enum('type', ['individual', 'company'])->default('individual');
            $table->string('company_name')->nullable();
            $table->string('state_registration')->nullable(); // inscrição estadual
            $table->string('municipal_registration')->nullable(); // inscrição municipal
            
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
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->decimal('current_balance', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F', 'O'])->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'type']);
            $table->index('document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
