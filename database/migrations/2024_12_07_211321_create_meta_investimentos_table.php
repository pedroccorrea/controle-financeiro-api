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
        Schema::create('meta_investimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nome')->comment('Nome da meta de investimento');
            $table->decimal('valor_alvo', 10, 2)->comment('Valor a ser alcançado');
            $table->date('data_inicio')->comment('Data inicial da meta');
            $table->date('data_fim')->comment('Data estipulada para a meta ser alcançada.');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_investimentos');
    }
};
