<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estabelecimento extends Model {

    protected $fillable = ['id','nome','url_logo','nivel','score','total_gasto','tipo_empresa','posicao'];
    protected $dates = ['deleted_at'];
}
