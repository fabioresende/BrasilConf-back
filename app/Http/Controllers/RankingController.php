<?php

namespace App\Http\Controllers;

use App\Http\Business\FornecedorBO;
use App\Http\Business\LojaBO;
use App\Http\Business\PedidoBO;
use App\Http\Business\ProdutoBO;
use App\Http\Business\RankingBO;
use App\Http\Business\UsuarioBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

class RankingController extends Controller
{
    private $produtoBO;
    private $fornecedorBO;
    private $rankingBO;
    private $pedidoBO;
    private $lojaBO;
    /**
     * RankingController constructor.
     */
    public function __construct() {
        $this->produtoBO = new ProdutoBO();
        $this->usuarioBO = new UsuarioBO();
        $this->fornecedorBO = new FornecedorBO();
        $this->lojaBO = new lojaBO();
        $this->rankingBO = new RankingBO();
        $this->pedidoBO = new PedidoBO();
    }

    public function estabelecimento(){
        $estabelecimento = $this->rankingBO->buscarEstabelecimento();
        return response()->json($estabelecimento);
    }

    public function rankear() {
        $ranking = $this->rankingBO->ranking();
        return response()->json($ranking,200);
    }

    public function historicoScore() {
        $historico = $this->rankingBO->historicoScore();
        return response()->json($historico,200);
    }

    public function graficos() {
        $usuarioLogado = JWTAuth::toUser();
        $retorno = null;
        if ($usuarioLogado->tipo_empresa == 2) {
            $loja = $this->lojaBO->buscarLoja();
            $retorno->pedidos = $this->pedidoBO->getPedidosConcluidosLoja($loja);
            $retorno->score = $this->rankingBO->historicoScore();
        } elseif ($usuarioLogado->tipo_empresa == 1) {
            $fornecedor = $this->fornecedorBO->buscarFornecedor();
            $retorno['pedidos'] = $this->pedidoBO->getPedidosConcluidosFornecedor($fornecedor);
            $retorno['produtos'] = $this->produtoBO->buscarProdutosMes($fornecedor);
            $retorno['score'] = $this->rankingBO->historicoScore();

        }
            return response()->json($retorno,200);
    }
}
