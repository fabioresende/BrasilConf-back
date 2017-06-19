<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LojaArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loja_area', function (Blueprint $table) {
            $table->integer('id_loja')->unsigned();
            $table->foreign('id_loja')
                ->references('id')
                ->on('lojas');
            $table->integer('id_area')->unsigned();
            $table->foreign('id_area')
                ->references('id')
                ->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
