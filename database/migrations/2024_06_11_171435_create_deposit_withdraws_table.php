<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposit_withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accountID')->constrained('accounts', 'id');
            $table->float('amount');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string('type');
            $table->bigInteger('refID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_withdraws');
    }
};
