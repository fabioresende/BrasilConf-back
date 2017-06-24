<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelaUsuarioAdm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
       Schema::create('usuarioAdministradores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('usuario','30')->unique();
            $table->string('senha','12');
            $table->string('cpf','11')->unique();
            $table->string('nome','50');
            $table->string('telefone','11');
            $table->boolean('status');
            $table->rememberToken();
            $table->timestamps();
        });
       Schema::create('fornecedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnpj','14')->unique();
            $table->string('nome','50');
            $table->string('telefone','11');
            $table->string('logradouro','30');
            $table->string('tipo_logradouro','8');
            $table->integer('numero');
            $table->string('cep','8');
            $table->string('cidade','50');
            $table->string('estado','50');
            $table->string('url_logo');
            $table->boolean('status');
            $table->integer('id_usuario_adm')->unsigned();
            $table->foreign('id_usuario_adm')
                ->references('id')
                ->on('usuarioAdministradores');
            $table->rememberToken();
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
        //
    }
}
