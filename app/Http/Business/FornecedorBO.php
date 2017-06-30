<?php
namespace App\Http\Business;

use App\Fornecedor;
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

class FornecedorBO {


    /**
     * FornecedorBO constructor.
     */
    public function __construct()
    {
    }

    public function buscarFornecedor() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $fornecedor = Fornecedor::where('id_usuario_adm', $usuarioLogado->id)->first();
        } else {
            $fornecedor = Fornecedor::where('id_usuario_adm', $usuarioLogado->id_usuarioadm)->first();
        }

        return $fornecedor;
    }

    public function salvar($atributos) {
        $fornecedorEncontrado = Fornecedor::find($atributos->id);
        if (!$fornecedorEncontrado) {
            $resposta = $this->salvarFornecedor($atributos);
        } else {
            $resposta = $this->atualizarFornecedor($fornecedorEncontrado,$atributos);
        }
        return $resposta;
    }
    private function salvarFornecedor($atributos) {
        $fornecedor = new Fornecedor();
        $fornecedor->fill($atributos->all());
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->id_tipo_usuario == 1) {
            $fornecedor->id_usuario_adm = JWTAuth::toUser()->id;
        } else {
            $fornecedor->id_usuario_adm = JWTAuth::toUser()->id_usuarioadm;
        }
        $retorno = $fornecedor->save();

        if ($retorno) {
            $resposta['msg'] = "Fornecedor salvo com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
        }
        else {
            $resposta['msg'] = "Não foi possível salvar fornecedor!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }



    public function atualizarFornecedor($fornecedor,$atributos) {
        $fornecedor->fill($atributos->all());
        $controle = $fornecedor->save();
        if ($controle) {
            $resposta['msg'] = "Fornecedor atualizado com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
        }
        else {
            $resposta['msg'] = "Não foi possível atualizar fornecedor!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }
}