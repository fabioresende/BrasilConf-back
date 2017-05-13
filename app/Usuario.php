<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['usuario','senha','tipo_usuario','cpf','telefone','status','nome'];
    protected $dates = ['deleted_at'];

}
