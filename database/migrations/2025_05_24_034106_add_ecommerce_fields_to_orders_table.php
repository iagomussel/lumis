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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->nullable()->after('total')->comment('Total amount for e-commerce compatibility');
            $table->string('stripe_payment_intent_id')->nullable()->after('payment_status')->comment('Stripe Payment Intent ID');
            $table->json('shipping_address_json')->nullable()->after('shipping_zip_code')->comment('Complete shipping address as JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'stripe_payment_intent_id', 'shipping_address_json']);
        });
    }
};
