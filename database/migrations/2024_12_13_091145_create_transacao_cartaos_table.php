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
        Schema::create('transacao_cartoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cartao_id');
            $table->tinyInteger('tipo')->comment('1: Débito, 2: Crédito');
            $table->decimal('valor', 10,2)->comment('Valor total da compra');
            $table->tinyInteger('quantidade_parcelas')->nullable()->comment('Quantidade de parcelas em que a compra foi dividida.');
            $table->date('data')->comment('Data da compra.');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cartao_id')->references('id')->on('cartoes')->onDelete('restrict');

            $table->index('user_id');
            $table->index('cartao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacao_cartoes');
    }
};
