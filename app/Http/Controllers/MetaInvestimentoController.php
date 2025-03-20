<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMetaInvestimentoRequest;
use App\Http\Requests\UpdateMetaInvestimentoRequest;
use App\Models\MetaInvestimento;
use App\Traits\apiResponseFormatter;
use Illuminate\Http\Request;

class MetaInvestimentoController extends Controller
{
    use apiResponseFormatter;

    public function __construct(protected MetaInvestimento $recurso) 
    {
        $this->recurso = $recurso;
    }

    public function index()
    {
        $recurso = $this->recurso->with('contribuicao')->get();
        return $this->formatResponse($recurso, 'Lista de metas recuperada com sucesso.');
    }

    public function store(StoreMetaInvestimentoRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());

        return $this->formatResponse($recurso, 'Meta registrada com sucesso', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Meta recuperada som sucesso', 200);
    }

    public function update(UpdateMetaInvestimentoRequest $request)
    {
        $recurso = $request->resource;
        $recurso->fill($request->validated());
        $recurso->save();

        return $this->formatResponse($recurso, $recurso->nome.' atualizada com sucesso.');
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();
        return $this->formatResponse(null, 'Meta deletada com sucesso.');
    }
}
