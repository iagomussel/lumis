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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->date('reference_date');
            $table->timestamp('processed_at')->nullable();
            $table->enum('status', ['pending', 'processed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('transactionable_type')->nullable();
            $table->unsignedBigInteger('transactionable_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type']);
            $table->index(['status']);
            $table->index(['reference_date']);
            $table->index(['account_id', 'type']);
            $table->index(['category']);
            $table->index(['transactionable_type', 'transactionable_id'], 'fin_trans_morph_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
