<?php

namespace App\Http\Controllers;

use App\Http\Business\LojaBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LojaController extends Controller {
     
    private $lojaBO;
    /**
     * LojaController constructor.
     */
    public function __construct()
    {
        $this->lojaBO = new LojaBO();
    }

    protected function lojaValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:50',
            'nome_fantasia' => 'required|max:50',
            'cnpj' => 'required',
            'cep' => 'required|max:8|min:8',
            'telefone' => 'required|max:11',
            'tipo_logradouro' => 'required|max:7',
            'logradouro' => 'required|max:50',
            'numero' => 'required',
            'url_logo' => 'required',
            'estado' => 'required',
            'cidade' => 'required'
        ]);
        return $validator;
    }

    public function buscarLoja() {
        $loja = $this->lojaBO->buscarLoja();
        if (!$loja) {
            return response()->json([
                'message' => 'Este usuário ainda não possui loja',
            ], 404);
        }
        return response()->json($loja);
    }

    public function buscarTodasAreas() {
        $areas = $this->lojaBO->buscarAreas();

        return response()->json($areas);
    }

    public function buscarAreasRelacionadas() {
        $areas = $this->lojaBO->buscarAreas();
        $areasRelacionadas = $this->lojaBO->buscarAreasRelacionadas($areas);
        return response()->json($areasRelacionadas);
    }

    public function salvarLoja(Request $atributos) {
        $validator = $this->lojaValidator($atributos);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erros de validacao do loja',
                'erros' => $validator->errors()
            ], 422);
        }
        $resposta = $this->lojaBO->salvar($atributos);
        return response()->json($resposta,200);
    }
}
