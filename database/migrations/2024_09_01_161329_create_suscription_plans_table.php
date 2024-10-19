<?php

use App\Constants\Periodicities;
use App\Constants\SubscriptionTerm;
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
        Schema::create('suscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('items')->nullable();
            $table->enum('periodicity', Periodicities::toArray());
            $table->decimal('amount', 12, 2);
            $table->enum('subscriptionTerm', SubscriptionTerm::toArray());
            $table->integer('lapse');
            $table->integer('attempts');
            $table->unsignedBigInteger('microsite_id');
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscription_plans');
    }
};
