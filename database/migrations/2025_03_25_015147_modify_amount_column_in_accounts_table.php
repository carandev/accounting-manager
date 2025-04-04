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
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->change();
        });
    }
};
