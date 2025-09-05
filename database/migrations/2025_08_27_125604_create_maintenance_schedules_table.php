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
    Schema::create('maintenance_schedules', function (Blueprint $table) {
        $table->id();
        $table->string('equipment_name');
        $table->string('type');
        $table->date('scheduled_date');
        $table->enum('status', ['pending', 'completed'])->default('pending');
        $table->string('technician_name');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
