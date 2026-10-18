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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('short')->nullable();
            $table->string('brand')->index();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('old_price')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->string('badge')->nullable();
            $table->string('badge_kind')->nullable();
            $table->string('installment')->nullable();
            $table->string('sku')->nullable();
            $table->string('stock')->default('in');
            $table->string('stock_text')->nullable();
            $table->text('description')->nullable();
            $table->json('specs')->nullable();
            $table->json('memory')->nullable();
            $table->json('colors')->nullable();
            $table->json('full_specs')->nullable();
            $table->json('images')->nullable();
            $table->string('emoji')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
