<?php

use App\Constants\SuscriptionsStatus;
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
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')
                ->references('id')
                ->on('suscription_plans')
                ->onDelete('cascade');
            $table->json('payer');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('microsite_id');
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');
            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');
            $table->enum('status', SuscriptionsStatus::toArray());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscriptions');
    }
};
