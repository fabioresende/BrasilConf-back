<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioAdministrador extends Model {
    protected $fillable = ['usuario', 'senha', 'id_tipo_usuario', 'cpf', 'telefone', 'status', 'nome', ''];
    protected $hidden = ['senha'];
    protected $dates = ['deleted_at'];

    public function fornecedor() {
        return $this->belongsTo('App\Fornecedor','id_usuario_adm');
    }
    //
}
