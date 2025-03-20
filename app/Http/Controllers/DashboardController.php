<?php

namespace App\Http\Controllers;

use App\Traits\apiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use apiResponseFormatter;

    public function index()
    {
        $dataAtual = now();
        $mesAtual = $dataAtual->format('m');
        $anoAtual = $dataAtual->format('Y');

        $totalEntradas = DB::table('recebimentos')->sum('valor');

        $totalGastosDiarios = DB::table('gasto_diarios')->sum('valor');

        $totalGastosRecorentes = DB::table('gasto_recorrentes')
        ->selectRaw('SUM(valor * TIMESTAMPDIFF(MONTH, created_at, ?)) as total', [$dataAtual])
        ->value('total') ?? 0;

        $saldoAtual = $totalEntradas - $totalGastosDiarios - $totalGastosRecorentes;

        $retorno = [
            'saldoAtual' => $saldoAtual,
            'receitaTotal' => $totalEntradas,
            'gastosTotais' => $totalGastosDiarios + $totalGastosRecorentes,
            'gastosDiariosTotais' => $totalGastosDiarios,
            'gastosRecorrentesTotais' => $totalGastosRecorentes,
        ];

        return $this->formatResponse($retorno, 'Totalizadores recuperados com sucesso.');
    }
}
