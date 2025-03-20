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
        Schema::create('contribuicao_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meta_investimento_id');
            $table->decimal('valor', 10,2)->comment('Valor contribuído para a meta');
            $table->timestamps();

            $table->foreign('meta_investimento_id')->references('id')->on('meta_investimentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribuicao_metas');
    }
};
