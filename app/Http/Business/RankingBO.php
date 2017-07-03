<?php

namespace App\Http\Business;

use App\Estabelecimento;
use App\Fornecedor;
use App\Loja;
use App\Pedido;
use App\Score;
use App\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Created by PhpStorm.
 * User: fabri_000
 * Date: 12/06/2017
 * Time: 16:28
 */
class RankingBO
{


    /**
     * FornecedorBO constructor.
     */
    public function __construct()
    {
    }

    public function buscarEstabelecimento() {
        $usuarioLogado = JWTAuth::toUser();
        $empresa = $this->getEmpresa();
        $estabelecimento = new Estabelecimento();
        $estabelecimento->tipo_empresa = $usuarioLogado->tipo_empresa;
        $estabelecimento->fill($empresa->getAttributes());
        $estabelecimento->nivel = $this->nivelarEstabelecimento($estabelecimento);
        $estabelecimentosRankeados = $this->ranking();
        for ($i = 0;$i< count($estabelecimentosRankeados);$i++) {
            if($estabelecimentosRankeados[$i]['id'] == $empresa->id){
                $estabelecimento->posicao = $i+1;
            }
        }
        return $estabelecimento;
    }

    public function nivelarEstabelecimento($estabelecimento) {
        $dinhero_gasto = $this->getTotalGasto($estabelecimento);
        $nivel = $this->nivelarPontuacao($dinhero_gasto);
        return $nivel;
    }

    private function nivelarPontuacao($dinheiro) {
        $nivel = null;
        switch ($dinheiro) {
            case $dinheiro < 100000:
                $nivel = 'Standard';
                break;
            case $dinheiro < 500000:
                $nivel = 'Gold';
                break;
            case $dinheiro < 1000000:
                $nivel = 'Platina';
                break;
            case $dinheiro >= 1000000:
                $nivel = 'Diamante';
                break;
        }
        return $nivel;
    }

    public function ranking() {
        $empresa = $this->getEmpresa();
        if ($empresa instanceof Fornecedor) {
            $empresas = Fornecedor::all();
        } else {
            $empresas = Loja::all();
        }
        $arr_emp = array();
        foreach ($empresas as $emp) {
            $arr_emp[] = $emp->getAttributes();
        }
        $ordenado = $this->array_sort($arr_emp, 'score', SORT_DESC);
        $arrRetorno = null;
        foreach ($ordenado as $index => $empresaOrdenada) {
            $empresaOrdenada['posicao'] = $index+1;
            $arrRetorno[] = $empresaOrdenada;
        }
        return $arrRetorno;
    }

    private function getEmpresa() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->tipo_empresa == 1) {
            if ($usuarioLogado->id_tipo_usuario == 1) {
                $empresa = Fornecedor::where('id_usuario_adm', $usuarioLogado->id)->first();
            } else {
                $empresa = Fornecedor::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
            }
        } else {
            if ($usuarioLogado->id_tipo_usuario == 1) {
                $empresa = Loja::where('id_usuario_adm', $usuarioLogado->id)->first();
            } else {
                $empresa = Loja::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
            }
        }
        return $empresa;
    }

    private function getTotalGasto($estabelecimento) {
        $dinhero_gasto = 0;
        if ($estabelecimento instanceof Fornecedor) {
            $pedidos = Pedido::where('fornecedor_id', $estabelecimento->id)->get();
        } else {
            $pedidos = Pedido::where('loja_id', $estabelecimento->id)->get();
        }
        foreach ($pedidos as $pedido) {
            $dinhero_gasto = $dinhero_gasto + $pedido->valor_total;
        }
        return $dinhero_gasto;
    }

    public function historicoScore() {
        $empresa = $this->getEmpresa();
        $retorno = null;
        if ($empresa instanceof Fornecedor) {
            $tipoEmpresa = 1;
        } else {
            $tipoEmpresa = 2;
        }
        for ($i = 1;$i<=12;$i++) {
            $scoresMes = Score::where('id_empresa',$empresa->id)
                ->where('tipo_empresa',$tipoEmpresa)
                ->whereMonth('created_at', '=', $i)
                ->get();
            $soma = 0;
            foreach ($scoresMes as $scoreMes){
                $soma = $scoreMes->pontos;
            }
            $retorno[] = $soma;
        }
        return $retorno;
    }

    public function array_sort($array, $on, $order = SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }
}