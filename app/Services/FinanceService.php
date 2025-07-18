<?php

namespace App\Services;

use App\Models\GastoDiario;
use App\Models\GastoRecorrente;
use App\Models\ParcelaCartao;
use App\Models\Recebimento;
use App\Models\TransacaoCartao;
use Illuminate\Support\Facades\Auth;

class FinanceService
{
    protected $FinanceService;
    protected $userId;

    public function __construct(
        protected GastoDiario $gastoDiario,
        protected GastoRecorrente $gastoRecorrente,
        protected TransacaoCartao $transacaoCartao,
        protected ParcelaCartao $parcelaCartao,
        protected Recebimento $recebimento,
        protected CacheService $cacheService
    ){
        $this->cacheService = $cacheService;
        $this->userId = Auth::id();
    }

    public function saldo()
    {
        
        $saldo = $this->cacheService->getOrSetSaldo();

        return $saldo;
        // $totalEntradas = $this->recebimento->sum('valor');
        // $totalGastosDiarios = $this->gastoDiario->sum('valor');
        // $totalGastosRecorrentes = $this->gastoRecorrente->sum('valor');

        // return [
        //     'saldoAtual' => $totalEntradas - $totalGastosDiarios - $totalGastosRecorrentes,
        //     'receitaTotal' => $totalEntradas,
        //     'gastosTotais' => $totalGastosDiarios + $totalGastosRecorrentes,
        //     'gastosDiariosTotais' => $totalGastosDiarios,
        //     'gastosRecorrentesTotais' => $totalGastosRecorrentes,
        // ];
    }

    public function gastosPorMes()
    {
        $totalGastosRecorrentes = $this->gastoRecorrente->sum('valor');
        
        $totalGastosDiarios = $this->parcelaCartao
            ->selectRaw("
                MONTH(data_vencimento) as num_mes,
                ELT(MONTH(data_vencimento), 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez') as mes, 
                SUM(valor) as total
            ")
            ->groupBy('num_mes', 'mes')->where((function($query) {
                $query->whereRaw('MONTH(data_vencimento) <= MONTH(NOW())');
                $query->whereRaw('YEAR(data_vencimento) = YEAR(NOW())');
        }))->orderBy('num_mes', 'asc')->get();

        foreach ($totalGastosDiarios as $gasto) {
            $gasto->total += $totalGastosRecorrentes;
        }
        return $totalGastosDiarios;
    }

    public function gastosPorCategoria()
    {
        $hoje = now();
        $mesAtual = $hoje->format('m');
        $anoAtual = $hoje->format('Y');

        $totalEntradas = $this->recebimento->sum('valor');

        $totalGastosDiarios = $this->gastoDiario->sum('valor');

        $totalGastosRecorentes = $this->gastoRecorrente
        ->selectRaw('SUM(valor * TIMESTAMPDIFF(MONTH, created_at, ?)) as total', [$hoje])
        ->value('total') ?? 0;

        $saldoAtual = $totalEntradas - $totalGastosDiarios - $totalGastosRecorentes;

        return  [
            'saldoAtual' => $saldoAtual,
            'receitaTotal' => $totalEntradas,
            'gastosTotais' => $totalGastosDiarios + $totalGastosRecorentes,
            'gastosDiariosTotais' => $totalGastosDiarios,
            'gastosRecorrentesTotais' => $totalGastosRecorentes,
        ];
    }

    public function extrato() {
        $hoje = now();
        $mesAtual = $hoje->format('m');
        $anoAtual = $hoje->format('Y');

        $recebimentos = $this->cacheService->getOrSetRecebimentos()->map(function ($item) {
            return (object) [
                'id' => $item->id,
                'nome' => $item->nome,
                'valor' => $item->valor,
                'tipo' => 'entrada',
                'data' => \Carbon\Carbon::parse($item->created_at)->format('Y-m-d')
            ];
        });

        $gastoRecorrente = $this->cacheService->getOrSetGastosRecorrentes()->flatMap(function ($item) use ($hoje) {
            $inicio = \Carbon\Carbon::parse($item->created_at)->day($item->data_vencimento);
            $fim = $hoje;

            $gastosRecorrentesExpandido = collect();

            while ($inicio->lessThanOrEqualTo($fim)) {
                $gastosRecorrentesExpandido->push((object) [
                    'id' => $item->id,
                    'nome' => $item->nome,
                    'valor' => $item->valor,
                    'tipo' => 'recorrente',
                    'data' => $inicio->format('Y-m-d')
                ]);

                $inicio->addMonth();
            }

            return $gastosRecorrentesExpandido;
        })->flatten(1);

        $gastoDiario = $this->gastoDiario->with('transacaoCartao.parcelas')->where('user_id', $this->userId)->get();
        $gastoDiarioTransformado = collect();

        foreach ($gastoDiario as $gasto) {
            $transacao = $gasto->transacaoCartao;
            if($transacao->quantidade_parcelas == 0 && $transacao->created_at <= $hoje) {
                $gastoDiarioTransformado->push((object) [
                    'id' => $gasto->id,
                    'nome' => $gasto->nome,
                    'valor' => $gasto->valor,
                    'tipo' => 'saida',
                    'data' => \Carbon\Carbon::parse($transacao->created_at)->format('Y-m-d')
                ]);
            } else {
                foreach ($transacao->parcelas as $parcela) {
                    if ($parcela->data_vencimento <= $hoje) {
                        $gastoDiarioTransformado->push((object) [
                            'id' => $gasto->id,
                            'nome' => $gasto->nome,
                            'valor' => $parcela->valor,
                            'parcela' => $parcela->numero_parcela,
                            'quantidade_parcelas' => $transacao->quantidade_parcelas,
                            'tipo' => 'saida',
                            'data' => \Carbon\Carbon::parse($parcela->data_vencimento)->format('Y-m-d')
                        ]);
                    }
                }
            }
        }

         $transacoes = $recebimentos
            ->merge($gastoRecorrente)
            ->merge($gastoDiarioTransformado)
            ->sortByDesc('data')
            ->groupBy('data');
        
        return $transacoes->all();
    }
}