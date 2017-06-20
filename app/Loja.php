<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loja extends Model {

    protected $fillable = [
        'id_loja',
        'nome',
        'nome_fantasia',
        'telefone',
        'cnpj',
        'logradouro',
        'tipo_logradouro',
        'numero',
        'cep',
        'cidade',
        'estado',
        'url_logo',
        'id_usuario_adm',
        'url_site'];
    protected $dates = ['deleted_at'];
    
    public function usuarioAdministrador() {
        return $this->hasOne('App\UsuarioAdministrador','id_usuario_adm');
    }
}
