<?php

namespace App\Http\Controllers;

use App\Models\ParcelaCartao;
use App\Http\Requests\StoreParcelaCartaoRequest;
use App\Http\Requests\UpdateParcelaCartaoRequest;
use App\Traits\apiResponseFormatter;
use Illuminate\Http\Request;

class ParcelaCartaoController extends Controller
{
    use apiResponseFormatter;
    
    public function __construct(protected ParcelaCartao $recurso)
    {
        $this->recurso = $recurso;
    }

    public function index()
    {
        $recurso = $this->recurso->get();
        return $this->formatResponse($recurso, 'Lista de parcelas recuperada com sucesso.');
    }

    public function store(StoreParcelaCartaoRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());
        return $this->formatResponse($recurso, 'Parcela registrada com sucesso.', 201);
    }

    public function show(Request $request)
    {
        $recurso = $request->resource;
        return $this->formatResponse($recurso, 'Parcela recuperada com sucesso.');
    }

    public function update(UpdateParcelaCartaoRequest $request)
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
        return $this->formatResponse(null, 'Parcela delatada com sucesso.');
    }
}
