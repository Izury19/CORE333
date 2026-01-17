<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('billing_invoices', function (Blueprint $table) {
        $table->boolean('sent_to_record_payment')->default(false);
        $table->unsignedBigInteger('record_payment_id')->nullable();
    });
}
    public function down()
    {
        Schema::table('billing_invoices', function (Blueprint $table) {
            $table->dropColumn(['sent_to_record_payment', 'record_payment_id']);
        });
    }
};
