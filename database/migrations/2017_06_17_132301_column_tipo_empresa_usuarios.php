<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ColumnTipoEmpresaUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
        });
        DB::table('tipo_empresa')->insert(
            array(
                'descricao' => 'Forneedor'
            )
        );
        DB::table('tipo_empresa')->insert(
            array(
                'descricao' => 'Loja'
            )
        );
        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer('tipo_empresa')->unsigned()->nullable();
            $table->foreign('tipo_empresa')
                ->references('id')
                ->on('tipo_empresa');
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
