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
        Schema::create('product_category_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->nullable()->references('id')->on('product_categories');
            $table->tinyInteger('min_user_age')->nullable(false)->default(1)->comment('Minimum user\'s age that can access the products under this product category');
            $table->tinyInteger('max_user_age')->nullable(false)->default(1)->comment('Maximum user\'s age that can access the products under this product category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category_access');
    }
};
