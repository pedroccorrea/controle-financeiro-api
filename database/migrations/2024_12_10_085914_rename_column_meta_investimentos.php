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
        Schema::table('meta_investimentos', function (Blueprint $table) {
            $table->renameColumn('valor_alvo', 'valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meta_investimentos', function (Blueprint $table) {
            $table->renameColumn('valor', 'valor_alvo');
        });
    }
};
