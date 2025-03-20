<?php

namespace App\Http\Controllers;

use App\Models\Recebimento;
use App\Http\Requests\StoreRecebimentoRequest;
use App\Http\Requests\UpdateRecebimentoRequest;
use App\Traits\apiResponseFormatter;
use Illuminate\Http\Request;

class RecebimentoController extends Controller
{
    use apiResponseFormatter;
    
    public function __construct(protected Recebimento $recurso)
    {
        $this->recurso = $recurso;
    }
    public function index(Request $request)
    {
        $porPagina = $request->get('por_pagina', 15);
        $query = $this->recurso->newQuery();
        if($request->filled('data_inicio')) {
            $query->where('created_at', '>=', $request->get('data_inicio'));
        }
        if($request->filled('data_fim')) {
            $query->where('created_at', '<=', $request->get('data_fim'));
        }
        $recurso = $query->paginate($porPagina);
        return $this->formatResponse($recurso, 'Lista de recebimentos recuperada com sucesso.');
    }

    public function store(StoreRecebimentoRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());

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
        return $this->formatResponse($recurso, 'Recebimento atualizado com sucesso'); 
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();
        return $this->formatResponse(null, 'Recebimento deletado com sucesso.');
    }
}
