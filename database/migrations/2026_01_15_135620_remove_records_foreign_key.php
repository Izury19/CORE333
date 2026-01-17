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
        $table->dropForeign(['invoice_id']);
        // Keep the column but make it regular integer
        $table->unsignedBigInteger('invoice_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('records', function (Blueprint $table) {
        $table->foreign('invoice_id')
              ->references('id')
              ->on('billing_invoices')
              ->onDelete('set null');
    });
}
};
