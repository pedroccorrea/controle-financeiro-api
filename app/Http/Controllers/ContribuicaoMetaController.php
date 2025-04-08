<?php

namespace App\Http\Controllers;

use App\Models\ContribuicaoMeta;
use App\Http\Requests\StoreContribuicaoMetaRequest;
use App\Http\Requests\UpdateContribuicaoMetaRequest;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;

class ContribuicaoMetaController extends Controller
{
    use ApiResponseFormatter;

    public function __construct(protected ContribuicaoMeta $recurso)
    {
        $this->recurso = $recurso;
    }

    public function index()
    {
        $recurso = $this->recurso->with('meta')->get();

        return $this->formatResponse($recurso, 'Lista de contribuições recuperada com sucesso');
    }

    public function store(StoreContribuicaoMetaRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());

        return $this->formatResponse($recurso, 'Contribuição registrada com sucesso.', 201);
    }

    public function show(Request $request)
    {
        $recurso = $request->resource->load('meta');
        return $this->formatResponse($recurso, 'Contribuição recuperada com sucesso');
    }

    public function update(UpdateContribuicaoMetaRequest $request)
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
        return $this->formatResponse($recurso, 'Contribuição deletada com sucesso.');
    }
}
