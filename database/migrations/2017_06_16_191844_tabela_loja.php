<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelaLoja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao','30')->unique();
            $table->timestamps();
        });
        Schema::create('loja', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnpj','14')->unique();
            $table->string('nome','50');
            $table->string('nome_fantasia','50');
            $table->string('telefone','11');
            $table->string('logradouro','30');
            $table->string('tipo_logradouro','8');
            $table->integer('numero');
            $table->string('cep','8');
            $table->string('cidade','50');
            $table->string('estado','50');
            $table->string('url_logo');
            $table->boolean('status');
            $table->string('url_site');
            $table->integer('id_usuario_adm')->unsigned();
            $table->foreign('id_usuario_adm')
                ->references('id')
                ->on('usuarios');
            $table->timestamps();
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer("id_loja")->unsigned()->nullable();
            $table->foreign('id_loja')
                ->references('id')
                ->on('loja')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('areaAtuacao');
        Schema::drop('loja');
    }
}
