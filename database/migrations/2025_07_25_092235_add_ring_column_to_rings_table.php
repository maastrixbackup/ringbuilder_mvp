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
        Schema::table('rings', function (Blueprint $table) {
            $table->string('ring_hover_img')->nullable()->after('ring_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rings', function (Blueprint $table) {
            $table->dropColumn('ring_hover_img');
        });
    }
};
