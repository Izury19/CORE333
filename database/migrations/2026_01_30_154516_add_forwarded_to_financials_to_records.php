<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->boolean('forwarded_to_financials')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('forwarded_to_financials');
        });
    }
};