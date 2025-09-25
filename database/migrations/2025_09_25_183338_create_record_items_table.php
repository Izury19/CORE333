<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('record_items', function (Blueprint $table) {
            $table->bigIncrements('record_item_id');
            $table->unsignedBigInteger('record_id'); // reference to records
            $table->string('description');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            // Foreign key to records
            $table->foreign('record_id')->references('record_id')->on('records')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('record_items');
    }
};
