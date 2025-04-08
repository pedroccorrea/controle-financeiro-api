<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseFormatter;
use App\Services\FinanceService;

class RelatorioController extends Controller
{
    use ApiResponseFormatter;

    protected $FinanceService;

    public function __construct(protected FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    
    public function gastosPorMes()
    {
        $gastPorMes = $this->financeService->gastosPorMes();
        return $this->formatResponse($gastPorMes, 'Total de gastos recorrentes recuperado com sucesso.');
    }

    public function gastosPorCategoria()
    {
        $gastosPorCategoria = $this->financeService->gastosPorCategoria();
        return $this->formatResponse($gastosPorCategoria, 'Totalizadores recuperados com sucesso.');
    }

    public function extrato() {
        $extrato = $this->financeService->extrato();
        return $this->formatResponse($extrato, 'Totalizadores recuperados com sucesso.');
    }
}
