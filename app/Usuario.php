<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Usuario extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword;

    protected $fillable = ['usuario','senha','id_tipo_usuario','cpf','telefone','status','nome','id_loja','id_fornecedor','id_usuarioadm','tipo_empresa'];
    protected $hidden = ['senha'];
    protected $dates = ['deleted_at'];

   public function fornecedor(){
        return $this->hasOne('App\Fornecedor');
    }
}
