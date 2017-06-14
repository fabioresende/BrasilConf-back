<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome','id_fornecedor','fabricante','preco','validade','descricao','status','quantidade','largura',"altura","url_foto"];
    protected $dates = ['deleted_at'];

    public function fornecedor(){
        return $this->belongsTo('App\Fornecedor',"id_fornecedor");
    }
}
