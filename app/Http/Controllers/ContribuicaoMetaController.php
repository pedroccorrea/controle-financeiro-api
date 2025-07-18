<?php

namespace App\Http\Controllers;

use App\Models\ContribuicaoMeta;
use App\Http\Requests\StoreContribuicaoMetaRequest;
use App\Http\Requests\UpdateContribuicaoMetaRequest;
use App\Services\CacheService;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContribuicaoMetaController extends Controller
{
    use ApiResponseFormatter;

    public function __construct(protected ContribuicaoMeta $recurso, protected CacheService $cacheService)
    {
        $this->recurso = $recurso;
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        // $recurso = $this->recurso->with('meta')->get();

        $recurso = $this->cacheService->getOrSetContribuicoesMeta();

        return $this->formatResponse($recurso, 'Lista de contribuições recuperada com sucesso');
    }

    public function total(Request $request)
    {
        $contribuicoes = $this->cacheService->getOrSetContribuicoesMeta();

        $total = $contribuicoes->sum('valor');

        return $this->formatResponse($total, 'Total de contribuições recuperado com sucesso.');
    }

    public function store(StoreContribuicaoMetaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $recurso = $this->recurso->create($data);

        $this->cacheService->clearMetasInvestimento();

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

        $this->cacheService->clearMetasInvestimento();

        return $this->formatResponse($recurso, 'Registro atualizado com sucesso.');
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();

        $this->cacheService->clearMetasInvestimento();

        return $this->formatResponse($recurso, 'Contribuição deletada com sucesso.');
    }
}
