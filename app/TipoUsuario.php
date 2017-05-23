<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $fillable = ['descricao'];

    public function usuarios() {
        return $this->belongsToMany('App\Usuario');
    }
}
