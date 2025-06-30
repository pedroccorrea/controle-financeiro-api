<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGastoRecorrenteRequest;
use App\Models\GastoRecorrente;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoRecorrenteController extends Controller
{
    use ApiResponseFormatter;

    public function __construct(protected GastoRecorrente $gastoRecorrente) {
        $this->gastoRecorrente = $gastoRecorrente;
    }
    

    public function index(Request $request)
    {
        $porPagina = $request->get('por_pagina', 15);
        $query = $this->gastoRecorrente->newQuery();
        if($request->filled('data_inicio')) {
            $query->where('created_at', '>=', $request->get('data_inicio'));
        }
        if($request->filled('data_fim')) {
            $query->where('created_at', '<=', $request->get('data_fim'));
        }
        $recurso = $query->paginate($porPagina);
        return $this->formatResponse($recurso, 'Lista de gastos recuperada com sucesso.');
    }

    public function store(StoreGastoRecorrenteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $gastoRecorrente = $this->gastoRecorrente->create($data);

        return $this->formatResponse($gastoRecorrente, 'Gasto registrado com sucesso', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Gasto recuperado som sucesso', 200);
    }

    public function update(Request $request)
    {
        $gastoRecorrente = $request->resource;
        try {
            if($request->method()=='PUT') {
                $request->validate($this->gastoRecorrente->rules(), $this->gastoRecorrente->feedback());
            } else if($request->method() == 'PATCH') {
                $regrasDinamicas = array_intersect_key($this->gastoRecorrente->rules(), $request->all());
                $request->validate($regrasDinamicas, $this->gastoRecorrente->feedback());
            }

            $gastoRecorrente->fill($request->all());
            $gastoRecorrente->save();

            return $this->formatResponse($gastoRecorrente, $gastoRecorrente->nome.' atualizado com sucesso.');
        } catch (\Exception $e) {
            return $this->formatResponse($e->getMessage(), 'Erro inesperado.', 500);
        }
    }

    public function destroy(Request $request)
    {
        $gastoRecorrente = $request->resource;
        $gastoRecorrente->delete();
        return $this->formatResponse(null, $gastoRecorrente->nome.' deletado com sucesso.');
    }
}
