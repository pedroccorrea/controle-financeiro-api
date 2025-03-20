<?php

namespace App\Services;

use App\Models\GastoDiario;
use App\Models\GastoRecorrente;
use App\Models\ParcelaCartao;
use App\Models\Recebimento;
use App\Models\TransacaoCartao;

class FinanceService
{
    public function __construct(
        protected GastoDiario $gastoDiario,
        protected GastoRecorrente $gastoRecorrente,
        protected TransacaoCartao $transacaoCartao,
        protected ParcelaCartao $parcelaCartao,
        protected Recebimento $recebimento,
    ){}

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
        $dataAtual = now();
        $mesAtual = $dataAtual->format('m');
        $anoAtual = $dataAtual->format('Y');

        $totalEntradas = $this->recebimento->sum('valor');

        $totalGastosDiarios = $this->gastoDiario->sum('valor');

        $totalGastosRecorentes = $this->gastoRecorrente
        ->selectRaw('SUM(valor * TIMESTAMPDIFF(MONTH, created_at, ?)) as total', [$dataAtual])
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
        $parcelas = $this->parcelaCartao->with(['transacao', 'cartao', 'gastoDiario'])->get();
        $recebimentos = $this->recebimento->get();
        $gastoRecorrente = $this->gastoRecorrente->get();

       $parcelas = $parcelas->map(function ($parcela) {
            return [
                'id' => $parcela->id,
                'tipo' => 'Saída', 
                'nome' => $parcela->gastoDiario->nome ?? 'Gasto desconhecido',
                'cartao' => $parcela->cartao->nome,
                'valor' => $parcela->valor,
                'data' => $parcela->data_vencimento ?? $parcela->created_at
            ];
       });

        $recebimentos = $recebimentos ->map(function ($recebimento) {
            return [
                'id' => $recebimento->id,
                'tipo' => 'Entrada',
                'nome' => $recebimento->nome,
                'valor' => $recebimento->valor,
                'data' => $recebimento->created_at
            ];
        });

        $gastosRecorrentesExpandido = collect();
        foreach ($gastoRecorrente as $gasto) {
            $dataInicio = \Carbon\Carbon::parse($gasto->data_vencimento);
            $dataAtual = now();

            while($dataInicio->lessThanOrEqualTo($dataAtual)) {
                $gastosRecorrentesExpandido->push([
                    'id' => $gasto->id,
                    'tipo' => 'Saída',
                    'nome' => $gasto->nome,
                    'valor' => $gasto->valor,
                    'data' => $dataInicio->format('Y-m-d')
                ]);

                $dataInicio->addMonth();
            }
        }


        $extrato = $parcelas->merge($recebimentos);
        $extrato = $extrato->merge($gastosRecorrentesExpandido);

        $extrato = $extrato->filter(fn($item) =>
            \Carbon\Carbon::parse($item['data'])->lessThanOrEqualTo(now())
        );

        $extrato = $extrato->sortByDesc(fn($item) => \Carbon\Carbon::parse($item['data']));

        return $extrato->values();
    }
}