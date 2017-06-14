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

    Route::get('usuarios','UsuarioController@buscarUsuarios');
    Route::get('auth/usuario','AuthController@buscarUsuarioLogado');
    Route::get('usuario/buscar/{idUsuario}','UsuarioController@buscarUsuariosId');
    Route::get('fornecedor','FornecedorController@buscarFornecedor');
    Route::post('fornecedor/salvar','FornecedorController@salvarFornecedor');
    Route::get('usuario/tipo-usuarios','UsuarioController@buscarTiposUsuario');
    Route::post('usuario/salvar','UsuarioController@salvarUsuario');
    Route::resource('companies', 'CompaniesController');
    Route::match(['get', 'options'], 'api/jobs', 'Api\JobsController@jobs');
});
Route::group(array('prefix' => 'api','middleware'=> 'cors'), function() {
    Route::post('auth/login', 'AuthController@authenticate');
});

Route::get('/', function () {
    return redirect('api');
});

