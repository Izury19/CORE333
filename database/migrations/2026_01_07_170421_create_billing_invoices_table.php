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
    Schema::create('billing_invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_uid')->unique();
        $table->unsignedBigInteger('contract_id')->nullable();
        $table->string('client_name');
        $table->string('equipment_type');
        $table->string('equipment_id');
        $table->integer('hours_used');
        $table->decimal('hourly_rate', 10, 2);
        $table->decimal('total_amount', 10, 2);
        $table->date('billing_period_start');
        $table->date('billing_period_end');
        $table->enum('status', ['pending', 'billed', 'disputed', 'paid'])->default('billed');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_invoices');
    }
};
