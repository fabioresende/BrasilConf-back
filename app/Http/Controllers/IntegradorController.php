<?php

namespace App\Http\Controllers;

use App\Http\Business\LojaBO;
use App\Http\Business\ProdutoBO;
use Illuminate\Http\Request;

use App\Http\Requests;

class IntegradorController extends Controller {
    //

    private $produtoBO;
    /**
     * IntegradorController constructor.
     */
    public function __construct()
    {
        $this->produtoBO = new ProdutoBO();
        $this->lojaBO = new LojaBO();
    }

    public function buscarCep($numCep){
        $curl = curl_init();
        $url = "http://viacep.com.br/ws/";
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url . "/" . $numCep."/json");
        $resposta = curl_exec($curl);
        $retorno = json_decode($resposta);
        if (!$retorno) {
            return response()->json([
                'success' => 'Erro',
                'msg' => 'Cep não encontrado',
            ], 404);
        }
        $dePara = explode(" ",$retorno->logradouro,2);
        $retorno->tipo_logradouro = $dePara[0];
        $retorno->logradouro = $dePara[1];
        curl_close($curl);
        return response()->json($retorno);
    }

    public function buscarFrete(Request $atributos){
        $loja = $this->lojaBO->buscarLoja();
        $fornecedor = $this->produtoBO->buscarFornecedorProduto($atributos->produto_id);
        $produto = $this->produtoBO->buscarProduto($atributos->produto_id);
        $data['nCdEmpresa'] = '';
        $data['sDsSenha'] = '';
        $data['sCepOrigem'] = '43820080';
        $data['sCepDestino'] = '43810040';
        $data['nVlPeso'] = '1';
        $data['nCdFormato'] = '1';
        $data['nVlComprimento'] = '16';
        $data['nVlAltura'] = $produto->altura;
        $data['nVlLargura'] = $produto->largura;
        $data['nVlDiametro'] = '0';
        $data['sCdMaoPropria'] = 's';
        $data['nVlValorDeclarado'] = '200';
        $data['sCdAvisoRecebimento'] = 'n';
        $data['StrRetorno'] = 'xml';
        $data['nCdServico'] = '40010';
        $data = http_build_query($data);

        $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';

        $curl = curl_init($url . '?' . $data);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resposta = curl_exec($curl);
        $retorno = json_decode($resposta);
        if (!$retorno) {
            return response()->json([
                'success' => 'Erro',
                'msg' => 'Cep não encontrado',
            ], 404);
        }
        curl_close($curl);
        return response()->json($retorno);
    }
}
