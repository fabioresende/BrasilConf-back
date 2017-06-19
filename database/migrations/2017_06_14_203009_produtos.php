<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Produtos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->timestamps();
        });

        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome','50');
            $table->string('fabricante','30');
            $table->double('preco');
            $table->date('validade')->nullable();
            $table->longText('descricao');
            $table->boolean('status');
            $table->integer('quantidade');
            $table->double('largura');
            $table->double('altura');
            $table->string('url_foto');
            $table->integer('id_departamento')->unsigned();
            $table->integer('id_fornecedor')->unsigned();
            $table->foreign('id_fornecedor')
                ->references('id')
                ->on('fornecedors');
            $table->timestamps();
        });        //
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
