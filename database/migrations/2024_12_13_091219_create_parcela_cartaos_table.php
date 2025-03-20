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
        Schema::create('parcela_cartoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transacao_cartao_id');
            $table->decimal('valor', 10,2)->comment('Valor da parcela');
            $table->date('data_vencimento');
            $table->integer('numero_parcela');
            $table->boolean('status')->default(false)->comment('False = Pendente, True = Pago');
            $table->timestamps();

            $table->foreign('transacao_cartao_id')->references('id')->on('transacao_cartoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcela_cartoes');
    }
};
