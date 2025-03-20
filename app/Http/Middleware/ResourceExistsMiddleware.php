<?php

namespace App\Http\Middleware;

use App\Traits\apiResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceExistsMiddleware
{
    use apiResponseFormatter;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $modelClass): Response
    {
        if(!class_exists($modelClass)) {
            return $this->formatResponse(null, "Classe {$modelClass} não encontrada.", 500);
        }

        $id=$request->route('id');
        $resource = app($modelClass)::find($id);
        
        if(!$resource) {
            return $this->formatResponse(null, 'O recurso solicitado não existe.', 404);
        }

        $request->merge(['resource' => $resource]);
        
        return $next($request);
    }
}
