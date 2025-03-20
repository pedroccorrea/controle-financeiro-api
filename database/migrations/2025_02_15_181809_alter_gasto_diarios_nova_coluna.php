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
        Schema::table('gasto_diarios', function (Blueprint $table) {
            $table->unsignedBigInteger('transacao_cartao_id')->nullable()->after('nome');
            $table->foreign('transacao_cartao_id')->references('id')->on('transacao_cartoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gasto_diarios', function (Blueprint $table) {
            $table->dropForeign(['transacao_cartao_id']);
            $table->dropColumn('transacao_cartao_id');
        });
    }
};
