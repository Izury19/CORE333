<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('billing_invoices', function (Blueprint $table) {
        $table->boolean('ai_duplicate_flag')->default(false);
    });
}
    public function down()
    {
        Schema::table('billing_invoices', function (Blueprint $table) {
            $table->dropColumn('ai_duplicate_flag');
        });
    }
};