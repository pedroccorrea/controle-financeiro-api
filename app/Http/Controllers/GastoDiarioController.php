<?php

namespace App\Http\Controllers;

use App\Models\GastoDiario;
use App\Models\TransacaoCartao;
use App\Http\Requests\StoreGastoDiarioRequest;
use App\Http\Requests\UpdateGastoDiarioRequest;
use App\Services\CacheService;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GastoDiarioController extends Controller
{
    use ApiResponseFormatter;
    
    public function __construct(protected GastoDiario $recurso, protected CacheService $cacheService)
    {
        $this->recurso = $recurso;
        $this->cacheService = $cacheService;
    }

    public function total(Request $request) {
        $gastos = $this->cacheService->getOrSetGastosDiarios();

        $total = $gastos->sum('valor');

        return $this->formatResponse($total, 'Total de gastos recuperado com sucesso.');
    }

    public function index(Request $request)
    {
        $gastos = $this->cacheService->getOrSetGastosDiarios();
        return $this->formatResponse($gastos, 'Lista de recebimentos recuperada com sucesso.');

        // $porPagina = $request->get('por_pagina', 15);
        // $query = $this->recurso->with('categoria:id,nome');
        // if($request->filled('data_inicio')) {
        //     $query->whereDate('created_at', '>=', $request->get('data_inicio'));
        // }
        // if($request->filled('data_fim')) {
        //     $query->whereDate('created_at', '<=', $request->get('data_fim'));
        // }
        // if($request->filled('categoria_id')) {
        //     $query->where('categoria_id', '=', $request->get('categoria_id'));
        // }
        // $gastos = $query->paginate($porPagina);
        
        // $gastos->through(function ($gasto) {
        //     return [
        //         'id' => $gasto->id,
        //         'nome' => $gasto->nome,
        //         'categoria_id' => $gasto->categoria_id,
        //         'transacao_cartao_id' => $gasto->transacao_cartao_id,
        //         'valor' => $gasto->valor,
        //         'created_at' => $gasto->created_at,
        //         'categoria' => $gasto->categoria->nome ?? null,
        //     ];
        // });
    }

    public function store(StoreGastoDiarioRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $recurso = $this->recurso->create($data);

        $this->cacheService->clearGastosDiarios();

        return $this->formatResponse($recurso, 'Gasto registrado com sucesso.', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Registro recuperado com sucesso');
    }

    public function update(UpdateGastoDiarioRequest $request)
    {
        $recurso = $request->resource;
        $validated = $request->validated();
        DB::transaction(function() use ($validated, $recurso) {
            if(isset($validated['transacao_cartao_id']) && $validated['transacao_cartao_id'] != $recurso->transacao_cartao_id) {
                $transacaoAntiga = TransacaoCartao::find($recurso->transacao_cartao_id);

                $recurso->fill($validated);
                $recurso->save();
                
                if($transacaoAntiga) $transacaoAntiga->delete();
            } else {
                $recurso->fill($validated);
                $recurso->save();
            }
        });

        $this->cacheService->clearGastosDiarios();

        return $this->formatResponse($recurso, 'Registro atualizado com sucesso');
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();
        
        $this->cacheService->clearGastosDiarios();

        return $this->formatResponse(null, 'Registro deletado com sucesso.');
    }
}
