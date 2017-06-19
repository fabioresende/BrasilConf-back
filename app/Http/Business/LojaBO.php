<?php

namespace App\Http\Business;

use App\Area;
use App\Loja;
use App\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Table;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Created by PhpStorm.
 * User: fabri_000
 * Date: 12/06/2017
 * Time: 16:28
 */
class LojaBO
{


    /**
     * LojaBO constructor.
     */
    public function __construct() {
    }

    public function buscarLoja() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id)->first();
        } else {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
        }

        return $loja;
    }

    public function buscarAreas() {
        $areas = Area::all();
        $retorno = array();
        foreach ($areas as $area) {
            $area->checked = false;
            $retorno[] = $area;
        }
        return $retorno;
    }

    public function buscarAreasRelacionadas($areas) {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id)->first();
        } else {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
        }
        $areasRelacionadas = DB::table('loja_area')->where('id_loja', $loja->id)->get();
        $retorno = array();
        foreach ($areas as $area) {
            foreach ($areasRelacionadas as $areaRelacionada) {
                if ($areaRelacionada->id_loja == $loja->id && $area->id == $areaRelacionada->id_area) {
                    $area->checked = true;
                }
            }
            $retorno[] = $area;
        }
        return $retorno;
    }

    public function salvar($atributos) {
        $lojaEncontrada = Loja::find($atributos->id);
        if (!$lojaEncontrada) {
            $resposta = $this->salvarLoja($atributos);
        } else {
            $resposta = $this->atualizarLoja($lojaEncontrada, $atributos);
        }
        return $resposta;
    }

    private function salvarLoja($atributos) {
        $loja = new Loja();
        $loja->fill($atributos->all());
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == "1") {
            $loja->id_usuario_adm = $usuarioLogado->id;
        } else {
            $loja->id_usuario_adm = $usuarioLogado->id_usuarioadm;
        }
        $retorno = $loja->save();

        if ($retorno) {
            $resposta['mensagem'] = "Loja salva com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        } else {
            $resposta['mensagem'] = "Erro: Não foi possível salvar loja!";
            $resposta['success'] = false;
            return $resposta;
        }
    }


    public function atualizarLoja($loja, $atributos) {
        $loja->fill($atributos->all());
        $controle = $loja->save();
        if ($controle) {
            $resposta['mensagem'] = "Loja atualizada com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        } else {
            $resposta['mensagem'] = "Erro: Não foi possível atualizar loja!";
            $resposta['success'] = false;
            return $resposta;
        }
    }
}