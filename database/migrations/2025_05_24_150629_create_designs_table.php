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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->string('file_type', 50);
            $table->json('dimensions')->nullable();
            $table->integer('dpi')->nullable();
            $table->string('color_profile')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('draft');
            $table->boolean('is_template')->default(false);
            $table->boolean('is_public')->default(false);
            $table->json('tags')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->string('version', 10)->default('1.0');
            $table->foreignId('parent_design_id')->nullable()->constrained('designs')->onDelete('set null');
            $table->integer('usage_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['is_template']);
            $table->index(['is_public']);
            $table->index(['created_by']);
            $table->index(['category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
