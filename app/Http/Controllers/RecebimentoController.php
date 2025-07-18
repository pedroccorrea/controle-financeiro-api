<?php

namespace App\Http\Controllers;

use App\Models\Recebimento;
use App\Http\Requests\StoreRecebimentoRequest;
use App\Http\Requests\UpdateRecebimentoRequest;
use App\Services\CacheService;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RecebimentoController extends Controller
{
    use ApiResponseFormatter;
    
    public function __construct(protected Recebimento $recurso, protected CacheService $cacheService)
    {
        $this->recurso = $recurso;
        $this->cacheService = $cacheService;
    }

    private function getRecebimentos() {
        $userId = Auth::id();
        return Cache::remember("recebimento_usuario_{$userId}", 2592000, function () use ($userId) {
            return \App\Models\Recebimento::where('user_id', $userId)->get();
        });
    }
    
    public function index(Request $request)
    {
        $entradas = $this->cacheService->getOrSetRecebimentos();

        return $this->formatResponse($entradas, 'Lista de recebimentos recuperada com sucesso.');

        // $porPagina = $request->get('por_pagina', 15);
        // $query = $this->recurso->newQuery();
        // if($request->filled('data_inicio')) {
        //     $query->where('created_at', '>=', $request->get('data_inicio'));
        // }
        // if($request->filled('data_fim')) {
        //     $query->where('created_at', '<=', $request->get('data_fim'));
        // }
        // $recurso = $query->paginate($porPagina);
    }

    public function total(Request $request) {
        $entradas = $this->cacheService->getOrSetRecebimentos();

        $total = $entradas->sum('valor');

        return $this->formatResponse($total, 'Total de recebimentos recuperado com sucesso.');
    }

    public function store(StoreRecebimentoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $recurso = $this->recurso->create($data);

        $this->cacheService->clearRecebimentos();

        return $this->formatResponse($recurso, 'Recebimento registrado com sucesso', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Recebimento recuperado com sucesso');
    }

    public function update(UpdateRecebimentoRequest $request)
    {
        $recurso = $request->resource;
        $recurso->fill($request->validated());
        $recurso->save();

        $this->cacheService->clearRecebimentos();

        return $this->formatResponse($recurso, 'Recebimento atualizado com sucesso'); 
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();

        $this->cacheService->clearRecebimentos();

        return $this->formatResponse(null, 'Recebimento deletado com sucesso.');
    }
}
