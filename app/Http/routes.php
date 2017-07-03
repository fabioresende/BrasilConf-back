<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(array('prefix' => 'api','middleware'=> ['cors','jwt.auth']), function()
{

    Route::get('/', function () {
        return response()->json(['message' => 'Jobs API', 'status' => 'Connected']);
    });

    /*
     * Rotas relacionada a autenticação
     * */
    Route::get('auth/usuario','AuthController@buscarUsuarioLogado');


    /*
     * Rotas relacionada ao usuário
     */
    Route::get('usuarios','UsuarioController@buscarUsuarios');
    Route::get('usuario/buscar/{idUsuario}','UsuarioController@buscarUsuariosId');
    Route::get('usuario/tipo-usuarios','UsuarioController@buscarTiposUsuario');
    Route::get('usuario/qtd-usuarios','UsuarioController@buscarQtdUsuarios');
    Route::post('usuario/salvar','UsuarioController@salvarUsuario');


    /*
     * Rotas relacionada ao fornecedor
     */
    Route::get('fornecedor','FornecedorController@buscarFornecedor');
    Route::post('fornecedor/salvar','FornecedorController@salvarFornecedor');

    /*
     * Rotas relacionada à loja
     */
    Route::get('loja','LojaController@buscarLoja');
    Route::post('loja/salvar','LojaController@salvarLoja');
    Route::get('loja/areas','LojaController@buscarTodasAreas');
    Route::get('loja/areas-relacionadas','LojaController@buscarAreasRelacionadas');


    /*
     * Rotas relacionada aos produtos
     */
    Route::get('produtos','ProdutoController@buscarProdutos');
    Route::get('produto/buscar/{idProduto}','ProdutoController@buscarProdutosid');
    Route::post('produto/salvar','ProdutoController@salvarProduto');
    Route::get('produto/departamentos','ProdutoController@buscarDepartamentos');
    Route::get('produtos/venda','ProdutoController@buscarProdutosVenda');
    Route::get('produtos-mes','ProdutoController@getProdutosCadastradosPorMes');

    /*
     * Rotas relacionada aos pedidos
     */
    Route::post('pedido/salvar','PedidoController@salvarPedido');
    Route::get('pedidos','PedidoController@buscarPedidos');
    Route::get('pedido/{idPedido}','PedidoController@buscarPedido');
    Route::post('pedido/confirmar','PedidoController@confirmarPedido');
    Route::post('pedido/finalizar','PedidoController@finalizarPedido');
    Route::get('pedido-pendentes','PedidoController@getPedidosPendentes');
    Route::get('pedido-concluidos','PedidoController@getNumPedidosConcluidosPorMes');

    /*
     * Rotas relacionada à cep
     */
    Route::get('cep/{numCep}','IntegradorController@buscarCep');
    Route::post('frete','IntegradorController@buscarFrete');

    Route::get('ranking','RankingController@rankear');
    Route::get('ranking/estabelecimento','RankingController@estabelecimento');
    Route::get('ranking/historico-score','RankingController@historicoScore');
});
Route::group(array('prefix' => 'api','middleware'=> 'cors'), function() {
    Route::post('auth/login', 'AuthController@authenticate');
});

Route::get('/', function () {
    return redirect('api');
});

