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

    public function buscarForncedor() {
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
        $fornecedor->id_usuarioadm = JWTAuth::toUser()->id_usuarioadm;
        $retorno = $fornecedor->save();

        if ($retorno) {
            $resposta['mensagem'] = "Fornecedor salvo com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível salvar fornecedor!";
            $resposta['success'] = false;
            return $resposta;
        }
    }



    public function atualizarFornecedor($fornecedor,$atributos) {
        $fornecedor->fill($atributos->all());
        $controle = $fornecedor->save();
        if ($controle) {
            $resposta['mensagem'] = "Fornecedor atualizado com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível atualizar fornecedor!";
            $resposta['success'] = false;
            return $resposta;
        }
    }
}