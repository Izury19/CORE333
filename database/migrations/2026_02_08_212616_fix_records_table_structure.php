<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('records', function (Blueprint $table) {
            // 1. Remove the incorrect foreign key if it exists
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'records' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND COLUMN_NAME = 'invoice_id'");

            foreach ($foreignKeys as $fk) {
                $table->dropForeign([$fk->CONSTRAINT_NAME]);
            }

            // 2. Ensure invoice_id is unsignedBigInteger
            $table->unsignedBigInteger('invoice_id')->change();

            // 3. Add correct foreign key to billing_invoices.id
            $table->foreign('invoice_id')
                  ->references('id')
                  ->on('billing_invoices')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });
    }
};