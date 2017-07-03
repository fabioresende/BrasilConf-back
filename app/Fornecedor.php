<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model {

    protected $fillable = ['id_fornecedor','nome','telefone','cnpj','logradouro','tipo_logradouro','numero','cep','cidade','estado','url_logo','id_usuario_adm','historia','score'];
    protected $dates = ['deleted_at'];
    
    public function usuarioAdministrador() {
        return $this->hasOne('App\UsuarioAdministrador','id_usuario_adm');
    }

}
