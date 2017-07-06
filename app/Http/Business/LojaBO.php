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
    public function __construct()
    {
    }

    public function buscarLoja()
    {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id)->first();
        } else {
            $loja = Loja::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
        }

        return $loja;
    }

    public function buscarAreas()
    {
        $areas = Area::all();
        $retorno = array();
        foreach ($areas as $area) {
            $area->checked = false;
            $retorno[] = $area;
        }
        return $retorno;
    }

    public function buscarAreasRelacionadas($areas)
    {
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

    public function buscarAreasRelacionadasVenda() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $loja = Loja::where("id_usuario_adm", $usuarioLogado->id)->first();
        } else {
            $loja = Loja::where("id_usuario_adm", $usuarioLogado->id_usuarioadm)->first();
        }
        $areas = DB::table('loja_area')
            ->where("id_loja", "=", $loja->id)
            ->get();
        $retorno = array();
        foreach ($areas as $area) {
            if($area->id_loja == $loja->id){
                $retorno[] = $area->id_area;
            }
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

    private function salvarLoja($atributos)
    {
        $loja = new Loja();
        $loja->fill($atributos->all());
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == "1") {
            $loja->id_usuario_adm = $usuarioLogado->id;
        } else {
            $loja->id_usuario_adm = $usuarioLogado->id_usuarioadm;
        }
        try{
            $retorno = $loja->save();
            $this->salvarAreas($loja, $atributos);
        } catch (\PDOException $e) {
           $resposta['msg'] = "Não foi possível salvar loja!";
           $resposta['success'] = "Erro";
           $resposta['detlhes_erro'] = $e;
           return $resposta;
        }    

            $resposta['msg'] = "Loja salva com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
    }


    public function atualizarLoja($loja, $atributos)
    {
        $loja->fill($atributos->all());
        try{
            $controle = $loja->save();            
            $this->salvarAreas($loja,$atributos);
        } catch (\PDOException $e) {
            $resposta['msg'] = "Não foi possível atualizar loja!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
            $resposta['msg'] = "Loja atualizada com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
    }

    private function salvarAreas($loja,$atributos)
    {
        $lojaArea = DB::table('loja_area');
        $lojaArea->where('id_loja', $atributos->id)->delete();
        foreach ($atributos->areas as $id) {
            $lojaArea->insert(['id_loja' => $loja->id, 'id_area' => $id]);
        }
    }
}