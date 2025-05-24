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
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('products', 'length')) {
                $table->decimal('length', 8, 2)->nullable()->after('weight')->comment('Length in cm');
            }
            if (!Schema::hasColumn('products', 'width')) {
                $table->decimal('width', 8, 2)->nullable()->after('length')->comment('Width in cm');
            }
            if (!Schema::hasColumn('products', 'height')) {
                $table->decimal('height', 8, 2)->nullable()->after('width')->comment('Height in cm');
            }
            if (!Schema::hasColumn('products', 'free_shipping')) {
                $table->boolean('free_shipping')->default(false)->after('height');
            }
            if (!Schema::hasColumn('products', 'shipping_notes')) {
                $table->text('shipping_notes')->nullable()->after('free_shipping');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'length', 
                'width',
                'height',
                'free_shipping',
                'shipping_notes'
            ]);
        });
    }
}; 