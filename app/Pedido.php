<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Pedido extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword;

    protected $fillable = ['id','quantidade','valor_total','produto_id','status','loja_id','fornecedor_id'];
    protected $dates = ['created_at','updated_at'];

   public function fornecedor(){
        return $this->hasOne('App\Fornecedor');
    }
}
