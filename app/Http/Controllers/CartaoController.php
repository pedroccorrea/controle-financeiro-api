<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Http\Requests\StoreCartaoRequest;
use App\Http\Requests\UpdateCartaoRequest;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;

class CartaoController extends Controller
{
    use ApiResponseFormatter;
    
    public function __construct(protected Cartao $recurso)
    {
        $this->recurso = $recurso;
    }

    public function index()
    {
        $recurso = $this->recurso->with('transacoes')->get();
        return $this->formatResponse($recurso, 'Lista de cartões recuperada com sucesso.');
    }

    public function store(StoreCartaoRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());
        return $this->formatResponse($recurso, 'Cartão registrado com sucesso.', 201);
    }

    public function show(Request $request)
    {
        $recurso = $request->resource->load('transacoes');
        return $this->formatResponse($recurso, 'Cartão recuperado com sucesso.');
    }

    public function update(UpdateCartaoRequest $request)
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
        return $this->formatResponse(null, 'Cartão delatado com sucesso.');
    }
}
