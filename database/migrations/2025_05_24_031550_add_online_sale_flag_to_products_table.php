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
        Schema::table('products', function (Blueprint $table) {
            // Apenas adiciona campos que não existem
            $table->text('short_description')->nullable()->after('description');
            $table->decimal('promotional_price', 10, 2)->nullable()->after('price');
            $table->timestamp('promotion_start')->nullable()->after('promotional_price');
            $table->timestamp('promotion_end')->nullable()->after('promotion_start');
            $table->integer('min_stock_alert')->default(5)->after('stock_quantity');
            $table->boolean('online_sale')->default(true)->after('status');
            $table->json('specifications')->nullable()->after('min_stock_alert');
            $table->decimal('rating', 3, 2)->default(0)->after('specifications');
            $table->integer('reviews_count')->default(0)->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'promotional_price',
                'promotion_start',
                'promotion_end',
                'min_stock_alert',
                'online_sale',
                'specifications',
                'rating',
                'reviews_count'
            ]);
        });
    }
};
