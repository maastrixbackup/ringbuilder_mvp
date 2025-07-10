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
        Schema::create('rings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->string('ring_style')->nullable();
            $table->string('ring_size')->nullable();
            $table->string('ring_karat')->nullable();
            $table->string('ring_weight')->nullable();
            $table->string('ring_color')->nullable();
            $table->decimal('ring_price', 10, 2)->nullable();
            $table->string('ring_image')->nullable();
            $table->enum('status', [0, 1])->default(0);
            $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rings');
    }
};
