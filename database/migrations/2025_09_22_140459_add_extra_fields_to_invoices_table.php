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
    Schema::table('invoices', function (Blueprint $table) {
        $table->string('terms_of_payment')->nullable();
        $table->text('client_address')->nullable();
        $table->text('note')->nullable();
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropColumn(['terms_of_payment', 'client_address', 'note']);
    });
}

};
