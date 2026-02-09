<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name');
            $table->string('equipment_id');
            $table->date('scheduled_date');
            $table->string('notification_type'); // 'upcoming', 'overdue', 'completed'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_notifications');
    }
};