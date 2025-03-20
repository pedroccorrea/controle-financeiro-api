<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Traits\apiResponseFormatter;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use apiResponseFormatter;

    public function __construct(protected Categoria $recurso) 
    {
        $this->recurso = $recurso;
    }

    public function index(Request $request)
    {
        if($request->filled('incluir_soma_gastos')) {{
            $categorias = $this->recurso->with('gastos')->get()->map(function($categoria) {
                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->nome,
                    'valor' => $categoria->gastos->sum('valor')
                ];
            });
        }} else {
            $categorias = $this->recurso->get();
        }

        return $this->formatResponse($categorias, 'Lista de categorias recuperada com sucesso.');
    }

    public function store(StoreCategoriaRequest $request)
    {
        $recurso = $this->recurso->create($request->validated());

        return $this->formatResponse($recurso, 'Categoria registrada com sucesso', 201);
    }

    public function show(Request $request)
    {
        return $this->formatResponse($request->resource, 'Categoria recuperada som sucesso', 200);
    }

    public function update(UpdateCategoriaRequest $request)
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
        return $this->formatResponse(null, 'Categoria deletada com sucesso.');
    }
}
