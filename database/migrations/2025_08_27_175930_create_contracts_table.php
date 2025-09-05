<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('contracts', function (Blueprint $table) {
        $table->id();
        $table->string('contract_title');
        $table->string('client_name');
        $table->date('start_date');
        $table->date('end_date');
        $table->string('equipment_type');
        $table->string('payment_type');
        $table->text('contract_details')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
