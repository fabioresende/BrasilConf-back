<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['descricao'];
    protected $dates = ['created_at','updated_at'];

    public function produtos() {
        return $this->hasMany('App\Produto');
    }
}
