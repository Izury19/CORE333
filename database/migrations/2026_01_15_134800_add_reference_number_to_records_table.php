<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // Migration file
public function up()
{
    Schema::table('records', function (Blueprint $table) {
        if (!Schema::hasColumn('records', 'reference_number')) {
            $table->string('reference_number')->nullable();
        }
    });
}

public function down()
{
    Schema::table('records', function (Blueprint $table) {
        if (Schema::hasColumn('records', 'reference_number')) {
            $table->dropColumn('reference_number');
        }
    });
}
};
