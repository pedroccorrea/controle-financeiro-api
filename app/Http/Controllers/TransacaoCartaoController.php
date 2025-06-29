<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGastoDiarioRequest;
use App\Models\TransacaoCartao;
use App\Http\Requests\StoreTransacaoCartaoRequest;
use App\Http\Requests\UpdateTransacaoCartaoRequest;
use App\Models\GastoDiario;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TransacaoCartaoService;

class TransacaoCartaoController extends Controller
{
    use ApiResponseFormatter;
    protected GastoDiario $gastoDiario;
    
    public function __construct(protected TransacaoCartao $recurso,  protected TransacaoCartaoService $transacaoCartaoService, GastoDiario $gastoDiario)
    {
        $this->recurso = $recurso;
        $this->gastoDiario = $gastoDiario;
    }

    public function index()
    {
        $recurso = $this->recurso->with('cartao')->with('parcelas')->get();
        return $this->formatResponse($recurso, 'Lista de transações recuperada com sucesso.');
    }

    public function store(StoreTransacaoCartaoRequest $request, StoreGastoDiarioRequest $gastoDiarioRequest)
    {
        $dadosTransacao = $request->validated();
        $dadosTransacao['user_id'] = Auth::id();

        $transacao = $this->transacaoCartaoService->criarTransacao($dadosTransacao);

        $dadosGastoDiario = $gastoDiarioRequest->validated();
        $dadosGastoDiario['transacao_cartao_id'] = $transacao->id;
        $dadosGastoDiario['user_id'] = Auth::id();
        $dadosGastoDiario['data'] = date('Y-m-d', strtotime($transacao->created_at));
        
        $gastoDiario = $this->gastoDiario->create($dadosGastoDiario);

        return $this->formatResponse($gastoDiario, 'Transação registrada com sucesso.', 201);
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
