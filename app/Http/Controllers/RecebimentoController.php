<?php

namespace App\Http\Controllers;

use App\Models\Recebimento;
use App\Http\Requests\StoreRecebimentoRequest;
use App\Http\Requests\UpdateRecebimentoRequest;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RecebimentoController extends Controller
{
    use ApiResponseFormatter;
    
    public function __construct(protected Recebimento $recurso)
    {
        $this->recurso = $recurso;
    }
    public function index(Request $request)
    {
        $userId = Auth::id();

        if (Cache::has("recebimento_usuario_{$userId}")) {
            Log::info("Cache já existe para o usuário {$userId}");
        } else {
            Log::info("Cache não existe para o usuário {$userId}, executando consulta no banco.");
        }

        return $entradas = Cache::remember("recebimento_usuario_{$userId}", 2592000, function () use ($userId) {
             Log::info("Consultando banco de dados para o usuário {$userId}");
            return \App\Models\Recebimento::where('user_id', $userId)->get();
        });
        // $porPagina = $request->get('por_pagina', 15);
        // $query = $this->recurso->newQuery();
        // if($request->filled('data_inicio')) {
        //     $query->where('created_at', '>=', $request->get('data_inicio'));
        // }
        // if($request->filled('data_fim')) {
        //     $query->where('created_at', '<=', $request->get('data_fim'));
        // }
        // $recurso = $query->paginate($porPagina);
        // return $this->formatResponse($recurso, 'Lista de recebimentos recuperada com sucesso.');
    }

    public function store(StoreRecebimentoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $recurso = $this->recurso->create($data);

        Cache::forget("recebimento_usuario_{$data['user_id']}");

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
