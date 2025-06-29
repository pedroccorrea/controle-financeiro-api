<?php
namespace App\Services;

use App\Models\TransacaoCartao;
use App\Models\ParcelaCartao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransacaoCartaoService
{
    protected TransacaoCartao $transacaoCartao;
    protected ParcelaCartao $parcelaCartao;

    public function __construct(TransacaoCartao $transacaoCartao, ParcelaCartao $parcelaCartao)
    {
        $this->transacaoCartao = $transacaoCartao;
        $this->parcelaCartao = $parcelaCartao;
    }

    public function criarTransacao(array $data): TransacaoCartao
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();
            $data['data'] = $data['data'] ?? date('Y-m-d');
            $transacao = $this->transacaoCartao->create($data);

            $qtdParcelas = $transacao->quantidade_parcelas;
            $dataVencimentoBase = date('Y-m-6');

            $vencimento = date('d') < 6 ? $dataVencimentoBase : date('Y-m-d', strtotime('+1 month', strtotime($dataVencimentoBase)));

            if($qtdParcelas  >= 1) {
                $valorParcela = round($transacao->valor / $qtdParcelas , 2);

                for ($i = 0; $i < $qtdParcelas ; $i++) {
                    $this->parcelaCartao->create([
                        'transacao_cartao_id' => $transacao->id,
                        'valor' => $valorParcela,
                        'data_vencimento' => date('Y-m-d', strtotime("+$i month", strtotime($vencimento))),
                        'numero_parcela' => $i + 1,
                        'status' => false,
                    ]);
                }
            }

            return $transacao;
        });
    }
}