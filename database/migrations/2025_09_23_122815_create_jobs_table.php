<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->enum('service_type', ['crane', 'trucking'])->default('crane');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->decimal('rate_per_hour', 10, 2)->default(0);
            $table->decimal('hours', 8, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
