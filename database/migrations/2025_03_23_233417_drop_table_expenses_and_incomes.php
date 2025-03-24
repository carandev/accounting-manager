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
         // Eliminar la primera tabla
        Schema::dropIfExists('incomes');

        // Eliminar la segunda tabla
        Schema::dropIfExists('expenses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('summary', 500);
            $table->decimal('amount');
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('summary', 500);
            $table->decimal('amount');
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
