<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->double('valor_total');
            $table->integer('quantidade');
            $table->integer('produto_id')->unsigned();
            $table->integer('loja_id')->unsigned();
            $table->string('status');
            $table->foreign('loja_id')
                ->references('id')
                ->on('lojas');
            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedidos');
    }
}
