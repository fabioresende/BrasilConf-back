<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao','30')->unique();
            $table->timestamps();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('usuario','30')->unique();
            $table->string('senha','12');
            $table->string('cpf','11')->unique();
            $table->string('nome','50');
            $table->string('telefone','11');
            $table->boolean('status');
            $table->integer('id_tipo_usuario')->unsigned();
            $table->foreign('id_tipo_usuario')
                ->references('id')
                ->on('tipo_usuarios');
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
        Schema::drop('users');
        Schema::drop('tipo_usuario');
    }
}
