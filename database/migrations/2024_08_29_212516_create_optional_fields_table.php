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
        Schema::create('optional_fields', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('field');
            $table->string('rule');
            /**$table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('optional_fields');
    }
};
