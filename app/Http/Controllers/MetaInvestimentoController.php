<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMetaInvestimentoRequest;
use App\Http\Requests\UpdateMetaInvestimentoRequest;
use App\Models\MetaInvestimento;
use App\Services\CacheService;
use App\Traits\ApiResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetaInvestimentoController extends Controller
{
    use ApiResponseFormatter;

    public function __construct(protected MetaInvestimento $recurso, protected CacheService $cacheService) 
    {
        $this->recurso = $recurso;
        $this->cacheService = $cacheService;
    }

    public function index()
    {
        // $recurso = $this->recurso->with('contribuicao')->get();
        $recurso = $this->cacheService->getOrSetMetasInvestimento();

        return $this->formatResponse($recurso, 'Lista de metas recuperada com sucesso.');
    }

    public function store(StoreMetaInvestimentoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $recurso = $this->recurso->create($data);

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

        $this->cacheService->clearMetasInvestimento();

        return $this->formatResponse($recurso, $recurso->nome.' atualizada com sucesso.');
    }

    public function destroy(Request $request)
    {
        $recurso = $request->resource;
        $recurso->delete();

        $this->cacheService->clearMetasInvestimento();
        
        return $this->formatResponse(null, 'Meta deletada com sucesso.');
    }
}
