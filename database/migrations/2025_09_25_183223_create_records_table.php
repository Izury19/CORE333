<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->bigIncrements('record_id');
            $table->unsignedBigInteger('invoice_id'); // reference to invoices
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->decimal('total', 10, 2);
            $table->string('payment_method')->nullable(); // gcash, paypal, etc.
            $table->string('status')->default('unpaid'); // copy ng invoice status
            $table->timestamps();

            // Foreign key to invoices
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
