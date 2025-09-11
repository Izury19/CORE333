<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');  // ito yung missing na column
            $table->string('description');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->string('client_name');
            $table->string('client_email');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->timestamps();

            // Foreign key constraint, optional pero recommended
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
