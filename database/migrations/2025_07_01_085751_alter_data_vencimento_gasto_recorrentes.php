<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gasto_recorrentes', function (Blueprint $table) {
            $table->dropColumn('data_vencimento');
        });

        Schema::table('gasto_recorrentes', function (Blueprint $table) {
            $table->integer('data_vencimento')->after(('valor'));
        });
    }

    public function down(): void
    {
        Schema::table('gasto_recorrentes', function (Blueprint $table) {
            $table->dropColumn('data_vencimento');
        });

        Schema::table('gasto_recorrentes', function (Blueprint $table) {
            $table->date('data_vencimento')->after(('valor'));
        });
    }
};
