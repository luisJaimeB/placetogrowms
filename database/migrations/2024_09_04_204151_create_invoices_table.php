<?php

use App\Constants\InvoicesStatus;
use App\Constants\SurchargeRate;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->enum('status', InvoicesStatus::toArray());
            $table->unsignedBigInteger('microsite_id');
            $table->foreign('microsite_id')
                ->references('id')
                ->on('microsites')
                ->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('identification_type_id');
            $table->foreign('identification_type_id')
                ->references('id')
                ->on('buyer_id_types')
                ->onDelete('cascade');
            $table->string('identification_number');
            $table->string('debtor_name');
            $table->string('email');
            $table->string('description');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
            $table->string('amount');
            $table->date('expiration_date');
            $table->date('surcharge_date');
            $table->boolean('surcharge_applied')->default(false)->nullable();
            $table->enum('surcharge_rate', SurchargeRate::toArray());
            $table->float('percent')->nullable();
            $table->float('additional_amount')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
