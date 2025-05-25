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
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_in_ecommerce')->default(false)->after('active');
            $table->text('internal_notes')->nullable()->after('show_in_ecommerce');
            $table->index(['show_in_ecommerce', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['show_in_ecommerce', 'active']);
            $table->dropColumn(['show_in_ecommerce', 'internal_notes']);
        });
    }
};
