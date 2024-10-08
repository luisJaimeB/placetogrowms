<?php

use App\Constants\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', PaymentStatus::toArray())->nullable();
            $table->integer('request_id')->unique()->nullable();
            $table->unsignedBigInteger('type');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
            $table->string('reference', 32)->unique()->nullable();
            $table->string('description', 100)->nullable();
            $table->date('date')->nullable();
            $table->json('buyer');
            $table->json('payer')->nullable();
            $table->string('return_url')->nullable();
            $table->string('proccess_url')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('microsite_id');
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');
            $table->string('payment_method');
            $table->string('return_id')->nullable();
            $table->string('cus_code')->nullable();
            $table->json('plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
