<?php
namespace App\Http\Business;

use App\Departamento;
use App\Fornecedor;
use App\Usuario;
use App\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Created by PhpStorm.
 * User: fabri_000
 * Date: 12/06/2017
 * Time: 16:28
 */

class ProdutoBO {


    /**
     * ProdutoBO constructor.
     */
    public function __construct()
    {
    }

    public function salvar($atributos,$fornecedor) {
        $produto = new Produto();
        $produtoEncontrado = $produto->find($atributos->id);
        if (!$produtoEncontrado) {
            $produto->id_fornecedor = $fornecedor->id;
            $resposta = $this->salvarProduto($produto,$atributos);
        }
        else {
            $resposta = $this->atualizarProduto($produtoEncontrado,$atributos);
        }
        return $resposta;
    }

    private function salvarProduto($produto,$atributos) {
        $produto->fill($atributos->all());
        try{
            $retorno = $produto->save();
        } catch (\PDOException $e) {
            $resposta['msg'] = "Não foi possível salvar o produto!";
            $resposta['success'] = 'ERRO';
            return $resposta;
        }

            $resposta['msg'] = "Produto salvo com sucesso!";
            $resposta['success'] = 'Sucesso';
            return $resposta;
        
    }



    public function atualizarProduto($produto,$atributos) {
        $produto->fill($atributos->all());
        try{ 
            $controle = $produto->save();
        }catch (\PDOException $e) {
            $resposta['msg'] = "Erro: Não foi possível atualizar produto!";
            $resposta['success'] = 'Erro';
            return $resposta;
        }    
            $resposta['msg'] = "Produto atualizado com sucesso!";
            $resposta['success'] = 'Sucesso';
            return $resposta;
    }

    public function buscarProduto($idProduto){
        $produto = new Produto();
        $produtoEncontrado = $produto::with(["departamento","fornecedor"])->find($idProduto);

        if (!$produtoEncontrado) {
            $resposta['msg'] = "Usuário não encontrado!";
            $resposta['success'] = 'Erro';
            return $resposta;
        } else {
            return $produtoEncontrado;
        }

    }

    public function buscarTodosProdutos($idFornecedor) {
        $produtosVinculados = Produto::with("departamento")->where('id_fornecedor',$idFornecedor)->get();
        return $produtosVinculados;
    }

    public function buscarDepartamentos() {
        $departamentos = Departamento::all();
        return $departamentos;
    }

    public function buscarProdutosVenda($areasRelacionadasLoja) {
        $registros = DB::table('produtos')
            ->join('departamentos', 'produtos.id_departamento', '=', 'departamentos.id')
            ->join('areas', 'departamentos.id_area', '=', 'areas.id')
            ->whereIn('areas.id',$areasRelacionadasLoja)
            ->select("produtos.*")->where('produtos.status',1)
            ->get();
        foreach ($registros as $registro){
            $registro->fornecedor = Fornecedor::where('id',$registro->id_fornecedor)->first();
            $registro->deparamento = Fornecedor::where('id',$registro->id_departamento)->first();
        }
        return $registros;
    }

    public function buscarFornecedorProduto($idProduto) {
        $produto = Produto::find($idProduto);
        $fornecedor = Fornecedor::find($produto->id_fornecedor);
        return $fornecedor;
    }

    public function buscarProdutosMes($fornecedor) {
        $retorno = null;
        for ($i = 1;$i<=12;$i++) {
            $produtosMes = Produto::where('id_fornecedor',$fornecedor->id)
                ->where('quantidade','>',0)
                ->whereMonth('created_at', '=', $i)
                ->get();
            $retorno[] = count($produtosMes);
        }
        return $retorno;
    }
}