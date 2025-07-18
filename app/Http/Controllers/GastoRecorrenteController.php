<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGastoRecorrenteRequest;
use App\Models\GastoRecorrente;
use App\Services\CacheService;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GastoRecorrenteController extends Controller
{
    use ApiResponseFormatter;

    public function __construct(protected GastoRecorrente $gastoRecorrente, protected CacheService $cacheService) {
        $this->gastoRecorrente = $gastoRecorrente;
        $this->cacheService = $cacheService;
    }

    public function index(Request $request)
    {
        $gastos = $this->cacheService->getOrSetGastosRecorrentes();
        return $this->formatResponse($gastos, 'Lista de gastos recuperada com sucesso.');
        // $porPagina = $request->get('por_pagina', 15);
        // $query = $this->gastoRecorrente->newQuery();
        // if($request->filled('data_inicio')) {
        //     $query->where('created_at', '>=', $request->get('data_inicio'));
        // }
        // if($request->filled('data_fim')) {
        //     $query->where('created_at', '<=', $request->get('data_fim'));
        // }
        // $recurso = $query->paginate($porPagina);
    }

    public function total(Request $request) {
        $gastos = $this->cacheService->getOrSetGastosRecorrentes();

        $total = $gastos->sum('valor');

        return $this->formatResponse($total, 'Total de recebimentos recuperado com sucesso.');
    }

    public function store(StoreGastoRecorrenteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $gastoRecorrente = $this->gastoRecorrente->create($data);

        $this->cacheService->clearGastosRecorrentes();

        return $this->formatResponse($gastoRecorrente, 'Gasto registrado com sucesso', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Gasto recuperado som sucesso', 200);
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $gastoRecorrente = $request->resource;
        try {
            $gastoRecorrente = $request->resource;
            $gastoRecorrente->fill($request->validated());
            $gastoRecorrente->save();

            $this->cacheService->clearGastosRecorrentes();
            return $this->formatResponse($gastoRecorrente, $gastoRecorrente->nome.' atualizado com sucesso.');
        } catch (\Exception $e) {
            return $this->formatResponse($e->getMessage(), 'Erro inesperado.', 500);
        }
    }

    public function destroy(Request $request)
    {
        $gastoRecorrente = $request->resource;
        $gastoRecorrente->delete();
        
        $this->cacheService->clearGastosRecorrentes();
        
        return $this->formatResponse(null, $gastoRecorrente->nome.' deletado com sucesso.');
    }
}
