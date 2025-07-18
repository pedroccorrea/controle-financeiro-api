<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ContribuicaoMetaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GastoDiarioController;
use App\Http\Controllers\GastoRecorrenteController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\ResourceExistsMiddleware;
use App\Http\Controllers\MetaInvestimentoController;
use App\Http\Controllers\ParcelaCartaoController;
use App\Http\Controllers\RecebimentoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\TransacaoCartaoController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('/recorrente', [GastoRecorrenteController::class, 'index']);
     Route::get('/recorrente/total', [GastoRecorrenteController::class, 'total']);
    Route::post('/recorrente', [GastoRecorrenteController::class, 'store']);
    
    Route::get('/meta_investimento', [MetaInvestimentoController::class, 'index']);
    Route::post('/meta_investimento', [MetaInvestimentoController::class, 'store']);
    
    Route::get('/gasto_diario', [GastoDiarioController::class, 'index']);
    Route::get('/gasto_diario/total', [GastoDiarioController::class, 'total']);
    Route::post('/gasto_diario', [GastoDiarioController::class, 'store']);
    
    Route::get('/recebimento', [RecebimentoController::class, 'index']);
    Route::get('/recebimento/total', [RecebimentoController::class, 'total']);
    Route::post('/recebimento', [RecebimentoController::class, 'store']);
    
    Route::get('/contribuicao_meta', [ContribuicaoMetaController::class, 'index']);
    Route::get('/contribuicao_meta/total', [ContribuicaoMetaController::class, 'total']);
    Route::post('/contribuicao_meta', [ContribuicaoMetaController::class, 'store']);
    
    Route::get('/transacao_cartao', [TransacaoCartaoController::class, 'index']);
    Route::post('/transacao_cartao', [TransacaoCartaoController::class, 'store']);
    
    Route::get('/parcela_cartao', [ParcelaCartaoController::class, 'index']);
    Route::post('/parcela_cartao', [ParcelaCartaoController::class, 'store']);
    
    Route::get('/cartao', [CartaoController::class, 'index']);
    Route::post('/cartao', [CartaoController::class, 'store']);
    
    Route::get('/categoria', [CategoriaController::class, 'index']);
    Route::post('/categoria', [CategoriaController::class, 'store']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::get('/relatorios/gastos-por-mes', [RelatorioController::class, 'gastosPorMes']);

    Route::get('/relatorios/saldo', [RelatorioController::class, 'saldo']);
    
    Route::get('/relatorios/gastos-por-categoria', [RelatorioController::class, 'gastosPorCategoria']);
    
    Route::get('/relatorios/extrato', [RelatorioController::class, 'extrato']);
    
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\GastoRecorrente')->group(function () {
        Route::get('/recorrente/{id}', [GastoRecorrenteController::class, 'show']);
        Route::match(['put', 'patch'], '/recorrente/{id}', [GastoRecorrenteController::class, 'update']);
        Route::delete('/recorrente/{id}', [GastoRecorrenteController::class, 'destroy']);
    
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\MetaInvestimento')->group(function () {
        Route::get('/meta_investimento/{id}', [MetaInvestimentoController::class, 'show']);
        Route::match(['put', 'patch'], '/meta_investimento/{id}', [MetaInvestimentoController::class, 'update']);
        Route::delete('/meta_investimento/{id}', [MetaInvestimentoController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\Recebimento')->group(function () {
        Route::get('/recebimento/{id}', [RecebimentoController::class, 'show']);
        Route::match(['put', 'patch'], '/recebimento/{id}', [RecebimentoController::class, 'update']);
        Route::delete('/recebimento/{id}', [RecebimentoController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\GastoDiario')->group(function () {
        Route::get('/gasto_diario/{id}', [GastoDiarioController::class, 'show']);
        Route::match(['put', 'patch'], '/gasto_diario/{id}', [GastoDiarioController::class, 'update']);
        Route::delete('/gasto_diario/{id}', [GastoDiarioController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\ContribuicaoMeta')->group(function () {
        Route::get('/contribuicao_meta/{id}', [ContribuicaoMetaController::class, 'show']);
        Route::match(['put', 'patch'], '/contribuicao_meta/{id}', [ContribuicaoMetaController::class, 'update']);
        Route::delete('/contribuicao_meta/{id}', [ContribuicaoMetaController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\TransacaoCartao')->group(function () {
        Route::get('/transacao_cartao/{id}', [TransacaoCartaoController::class, 'show']);
        Route::match(['put', 'patch'], '/transacao_cartao/{id}', [TransacaoCartaoController::class, 'update']);
        Route::delete('/transacao_cartao/{id}', [TransacaoCartaoController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\ParcelaCartao')->group(function () {
        Route::get('/parcela_cartao/{id}', [ParcelaCartaoController::class, 'show']);
        Route::match(['put', 'patch'], '/parcela_cartao/{id}', [ParcelaCartaoController::class, 'update']);
        Route::delete('/parcela_cartao/{id}', [ParcelaCartaoController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\Cartao')->group(function () {
        Route::get('/cartao/{id}', [CartaoController::class, 'show']);
        Route::match(['put', 'patch'], '/cartao/{id}', [CartaoController::class, 'update']);
        Route::delete('/cartao/{id}', [CartaoController::class, 'destroy']);
    });
    Route::middleware(\App\Http\Middleware\ResourceExistsMiddleware::class.':App\\Models\\Categoria')->group(function () {
        Route::get('/categoria/{id}', [CategoriaController::class, 'show']);
        Route::match(['put', 'patch'], '/categoria/{id}', [CategoriaController::class, 'update']);
        Route::delete('/categoria/{id}', [CategoriaController::class, 'destroy']);
    });
});

