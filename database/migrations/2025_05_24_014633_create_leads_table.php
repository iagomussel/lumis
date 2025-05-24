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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable(); // cargo na empresa
            
            // Status do lead
            $table->enum('status', [
                'new', 'contacted', 'qualified', 'proposal_sent', 
                'negotiation', 'won', 'lost', 'unqualified'
            ])->default('new');
            
            // Origem do lead
            $table->enum('source', [
                'website', 'social_media', 'email_campaign', 'referral', 
                'trade_show', 'cold_call', 'organic_search', 'paid_ads', 'other'
            ])->nullable();
            
            // Pontuação do lead (lead scoring)
            $table->integer('score')->default(0);
            
            // Informações comerciais
            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->integer('probability')->default(0); // percentual de 0 a 100
            $table->date('expected_close_date')->nullable();
            
            // Responsável
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Datas de acompanhamento
            $table->timestamp('last_contact_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();
            
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'source']);
            $table->index(['score', 'probability']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
