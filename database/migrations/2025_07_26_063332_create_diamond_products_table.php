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
        Schema::create('diamond_products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->integer('grown_type')->nullable();
            $table->string('shape')->nullable();
            $table->string('cut')->nullable();
            $table->string('color')->nullable();
            $table->string('carat')->nullable();
            $table->string('clarity')->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->string('img_one')->nullable();
            $table->string('img_two')->nullable();
            $table->string('img_three')->nullable();
            $table->string('img_four')->nullable();
            $table->enum('status', [1, 0])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diamond_products');
    }
};
