<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('frequency', ['Monthly', 'Quarterly', 'Yearly']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_types');
    }
};
