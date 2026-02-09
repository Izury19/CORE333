<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('contract_type');
            $table->string('counterparty');
            $table->date('effective_date');
            $table->date('expiration_date');
            $table->text('description')->nullable();
            $table->enum('legal_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('submitted_by');
            $table->timestamps();
            
            // Optional: Foreign key to users table
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};