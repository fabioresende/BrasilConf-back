<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {

    protected $fillable = ['id_area','descricao'];
    protected $dates = ['created_at','updated_at'];
    
    public function lojas() {
        return $this->belongsToMany('App\Loja','loja_area','id_loja',"id_area");
    }
}
