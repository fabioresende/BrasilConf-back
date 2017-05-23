<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['usuario','senha','id_tipo_usuario','cpf','telefone','status','nome'];
    protected $hidden = ['senha'];
    protected $dates = ['deleted_at'];

    public function tipoUsuario() {
        return $this->hasOne('App\TipoUsuario');
    }
}
