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
            $table->foreignId('product_category_id')->nullable()->references('id')->on('product_categories');
            $table->string('name', 100)->nullable(false);
            $table->text('description')->nullable();
            $table->tinyInteger('quantity')->nullable(false)->default(1);
            $table->unsignedDecimal('price', $precision = 8, $scale = 2)->nullable(false)->default(0);

            $table->foreignId('created_by')->nullable()->references('id')->on('users')->comment('Created by user.');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->comment('Updated by user.');
            $table->foreignId('deleted_by')->nullable()->references('id')->on('users')->comment('Deleted by user.');

            $table->softDeletes('deleted_at')->nullable();
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
