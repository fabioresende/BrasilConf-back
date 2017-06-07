<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model {

    protected $fillable = ['id_fornecedor','nome','telefone','cnpj','logradouro','tipo_logradouro','numero','cep','cidade','estado','urlLogo','id_usuario_adm'];
    protected $dates = ['deleted_at'];
    
    public function usuarioAdministrador() {
        return $this->hasOne('App\UsuarioAdministrador','id_usuario_adm');
    }
}