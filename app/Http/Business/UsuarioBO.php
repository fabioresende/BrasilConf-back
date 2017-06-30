<?php
namespace App\Http\Business;

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

class UsuarioBO {


    /**
     * UsuarioBO constructor.
     */
    public function __construct()
    {
    }

    public function salvar($atributos) {
        $usuario = new Usuario();
        $usuarioEncontrado = $usuario->find($atributos->id);
        if (!$usuarioEncontrado) {
            $resposta = $this->salvarUsuario($usuario,$atributos);
        }
        else {
            $resposta = $this->atualizarUsuario($usuarioEncontrado,$atributos);
        }
        return $resposta;
    }

    private function salvarUsuario($usuario,$atributos) {
        $usuario->fill($atributos->all());
        $usuarioLogado = JWTAuth::toUser();
        $usuario->id_usuarioadm = $usuarioLogado->id;
        $usuario->tipo_empresa = $usuarioLogado->tipo_empresa;
        try{
            $retorno = $usuario->save();
        } catch (\PDOException $e) {
            $resposta['msg'] = "Erro: Não foi possível salvar usuário!";
            $resposta['success'] = "Erro";
            return $resposta;
        }

            $resposta['msg'] = "Usuário salvo com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
    }



    public function atualizarUsuario($usuario,$atributos) {
        $usuario->fill($atributos->all());
        try {
            $controle = $usuario->save();
        } catch (\PDOException $e) {
            $resposta['msg'] = "Erro: Não foi possível salvar usuário!";
            $resposta['success'] = "Erro";
        }
            $resposta['msg'] = "Usuário atualizado com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
    }

    public function buscarUsuario($idUsuario){
        $usuario = new Usuario();
        $usuarioEncontrado = $usuario->find($idUsuario);

        if (!$usuarioEncontrado) {
            $resposta['msg'] = "Usuário não encontrado!";
            $resposta['success'] = "Erro";
            return $resposta;
        } else {
            return $usuarioEncontrado;
        }

    }

    public function buscarTiposUsuario() {
        $tiposUsuario = DB::table('tipo_usuarios')->select('descricao')->get();
        foreach ($tiposUsuario as $tipoUsuario) {
            $descricao[] = $tipoUsuario->descricao;
        }
        return $descricao;
    }

    public function buscarTodosUsuarios($idUsuarioAdm) {
        $usuariosVinculados = Usuario::where('id_usuarioadm',$idUsuarioAdm)->get();
        return $usuariosVinculados;
    }

    public function buscarUsuarioAdm() {
        $usuario = JWTAuth::toUser();
        if($usuario->id_tipo_usuario == 1) {
            $usuarioAdm = $usuario;
        } else{
            $usuarioEncontrado = Usuario::find($usuario->id_usuarioadm);
            $usuarioAdm = $usuarioEncontrado;
        }
        return $usuarioAdm;
    }
}