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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->text('description')->nullable();

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
        Schema::dropIfExists('product_categories');
    }
};
