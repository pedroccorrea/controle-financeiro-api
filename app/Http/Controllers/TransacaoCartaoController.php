<?php

namespace App\Http\Controllers;

use App\Models\TransacaoCartao;
use App\Http\Requests\StoreTransacaoCartaoRequest;
use App\Http\Requests\UpdateTransacaoCartaoRequest;
use App\Models\ParcelaCartao;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;

class TransacaoCartaoController extends Controller
{
    use ApiResponseFormatter;
    
    public function __construct(protected TransacaoCartao $recurso)
    {
        $this->recurso = $recurso;
    }

    public function index()
    {
        $recurso = $this->recurso->with('cartao')->with('parcelas')->get();
        return $this->formatResponse($recurso, 'Lista de transações recuperada com sucesso.');
    }

    public function store(StoreTransacaoCartaoRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());
        $qtdParcelas = $recurso->quantidade_parcelas;
        $dataVencimento = date('Y-m-6');
        
        if(date('d') < 6) {
            $vencimento = $dataVencimento;
        } else {
            $vencimento = date('Y-m-d', strtotime('+1 month', strtotime($dataVencimento)));
        }
        if($qtdParcelas >= 1) {
            $valorParcela = round($recurso->valor / $qtdParcelas, 2);
            for ($i=0; $i < $qtdParcelas; $i++) { 
                ParcelaCartao::create([
                    'transacao_cartao_id' => $recurso->id,
                    'valor' => $valorParcela,
                    'data_vencimento' => date('Y-m-d', strtotime("+$i month", strtotime($vencimento))),
                    'numero_parcela' => $i+1,
                    'status' => false
                ]);
            }
        }
        return $this->formatResponse($recurso, 'Transação registrada com sucesso.', 201);
    }

    public function show(Request $request)
    {
        $recurso = $request->resource->load('cartao')->load('parcelas');
        return $this->formatResponse($recurso, 'Transação recuperada com sucesso.');
    }

    public function update(UpdateTransacaoCartaoRequest $request)
    {
        $recurso = $request->resource;
        $recurso->fill($request->validated());
        $recurso->save();
        return $this->formatResponse($recurso, 'Registro atualizado com sucesso.');

    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();
        return $this->formatResponse(null, 'Transação delatada com sucesso.');
    }
}
