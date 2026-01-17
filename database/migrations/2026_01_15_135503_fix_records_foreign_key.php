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
    // Drop the existing foreign key
    Schema::table('records', function (Blueprint $table) {
        $table->dropForeign(['invoice_id']);
        $table->dropIndex(['invoice_id']); // Drop index if exists
    });

    // Recreate foreign key pointing to billing_invoices
    Schema::table('records', function (Blueprint $table) {
        $table->foreignId('invoice_id')->nullable()->change();
        $table->foreign('invoice_id')
              ->references('id') // billing_invoices uses 'id' as primary key
              ->on('billing_invoices')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('records', function (Blueprint $table) {
        $table->dropForeign(['invoice_id']);
    });
}
};
