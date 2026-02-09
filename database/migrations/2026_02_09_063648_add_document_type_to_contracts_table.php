<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Add document_type column with default 'contract'
            $table->string('document_type')->default('contract')->after('submitted_by');
        });
        
        // Update existing records to have document_type = 'contract'
        DB::table('contracts')->whereNull('document_type')->update(['document_type' => 'contract']);
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
};