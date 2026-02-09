<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->decimal('ai_risk_score', 3, 2)->nullable()->default(0.00);
            $table->date('ai_predicted_failure_date')->nullable();
            $table->text('ai_recommendations')->nullable();
        });
    }

    public function down()
    {
        Schema::table('maintenance_schedules', function (Blueprint $table) {
            $table->dropColumn(['ai_risk_score', 'ai_predicted_failure_date', 'ai_recommendations']);
        });
    }
};