<?php
namespace App\Services;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\GastoDiario;
use App\Models\Recebimento;
use App\Models\GastoRecorrente;

class CacheService
{
    protected $userId;

    public function __construct()
    {
        $this->userId = Auth::id();
    }

    //======= Recebimentos =======
    public function getOrSetRecebimentos()
    {
        return Cache::remember("recebimentos_usuario{$this->userId}", 2592000, function () {
            return Recebimento::where('user_id', $this->userId)->get();
        });
    }

    public function clearRecebimentos() 
    {
        Cache::forget("recebimentos_usuario{$this->userId}");
    }

    //======= Gastos Diários =======
    public function getOrSetGastosDiarios()
    {
        return Cache::remember("gasto_diario_usuario{$this->userId}", 2592000, function () {
            return GastoDiario::where('user_id', $this->userId)->get();
        });
    }

    public function clearGastosDiarios() 
    {
        Cache::forget("gasto_diario_usuario{$this->userId}");
    }

    //======= Gastos Recorrentes =======
    public function getOrSetGastosRecorrentes()
    {
        return Cache::remember("gasto_recorrente_usuario{$this->userId}", 2592000, function () {
            return GastoRecorrente::where('user_id', $this->userId)->get();
        });
    }

    public function clearGastosRecorrentes() 
    {
        Cache::forget("gasto_recorrente_usuario{$this->userId}");
    }

    //======= Metas de Investimento =======
    public function getOrSetMetasInvestimento()
    {
        return Cache::remember("meta_investimento_usuario{$this->userId}", 2592000, function () {
            return \App\Models\MetaInvestimento::where('user_id', $this->userId)->with('contribuicao')->get();
        });
    }

    public function clearMetasInvestimento() 
    {
        Cache::forget("meta_investimento_usuario{$this->userId}");
    }

    //======= Contribuições de Metas =======
    public function getOrSetContribuicoesMeta()
    {
        return Cache::remember("contribuicao_meta_usuario{$this->userId}", 2592000, function () {
            return \App\Models\ContribuicaoMeta::whereHas('meta', function ($query) {
               $query->where('user_id', $this->userId);  
            })->with('meta')->get();
        });
    }
    
    public function clearContribuicoesMeta() 
    {
        Cache::forget("contribuicao_meta_usuario{$this->userId}");
    }

    //======= Saldo =======

    protected function calcularTotalCartao()
    {
        $total = 0;
        $transacoes = \App\Models\TransacaoCartao::where('user_id', $this->userId)->get();
        
        foreach ($transacoes as $transacao) {
            if($transacao->tipo == 1 && $transacao->quantidade_parcelas == 0) {
                $total += $transacao->valor;
            } else {
                $parcelas = \App\Models\ParcelaCartao::where('transacao_cartao_id', $transacao->id)
                    ->whereDate('data_vencimento', '<=', now())
                    ->get();

                $total += $parcelas->sum('valor');
            }
        }

        return $total;
    }

    public function getOrSetSaldo()
    {
        return Cache::remember("saldo_usuario{$this->userId}", 86400, function () {
            $totalReceitas = Cache::get("recebimentos_usuario{$this->userId}", collect())->sum('valor');

            $gastosRecorrentes = Cache::get("gasto_recorrente_usuario{$this->userId}", collect());
            $hoje = now()->day;
            $totalGastosRecorrentes = $gastosRecorrentes->filter(fn($gasto) => $gasto->data_vencimento <= $hoje)->sum('valor');

            $totalGastosDiarios = $this->calcularTotalCartao();;
            return $totalReceitas - $totalGastosDiarios - $totalGastosRecorrentes;
        });
    }
    public function clearSaldo() 
    {
        Cache::forget("saldo_usuario{$this->userId}");
    }
}