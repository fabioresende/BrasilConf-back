<?php

namespace App\Http\Controllers;

use App\Http\Business\FornecedorBO;
use App\Http\Business\LojaBO;
use App\Http\Business\ProdutoBO;
use App\Http\Business\RankingBO;
use App\Http\Business\UsuarioBO;
use Illuminate\Http\Request;

use App\Http\Requests;

class RankingController extends Controller
{
    private $produtoBO;
    private $fornecedorBO;
    private $rankingBO;

    /**
     * RankingController constructor.
     */
    public function __construct() {
        $this->produtoBO = new ProdutoBO();
        $this->usuarioBO = new UsuarioBO();
        $this->fornecedorBO = new FornecedorBO();
        $this->lojaBO = new lojaBO();
        $this->rankingBO = new RankingBO();
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
}
