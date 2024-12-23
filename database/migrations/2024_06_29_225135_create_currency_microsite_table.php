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
        Schema::create('currency_microsite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id');
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites');
            $table->foreignId('currency_id');
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_microsite');
    }
};
