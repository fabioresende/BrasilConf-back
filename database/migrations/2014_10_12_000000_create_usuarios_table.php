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

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao','30')->unique();
            $table->timestamps();
        });
        DB::table('areas')->insert(
            array(
                'descricao' => 'Eletronicos'
            )
        );
        DB::table('areas')->insert(
            array(
                'descricao' => 'Vestuário'
            )
        );
        DB::table('areas')->insert(
            array(
                'descricao' => 'Móveis'
            )
        );
        DB::table('areas')->insert(
            array(
                'descricao' => 'Instrumentos'
            )
        );
        DB::table('areas')->insert(
            array(
                'descricao' => 'Automotivos'
            )
        );
        DB::table('areas')->insert(
            array(
                'descricao' => 'Alimentício'
            )
        );

        DB::table('areas')->insert(
            array(
                'descricao' => 'Materiais de Construção'
            )
        );

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

        Schema::create('tipo_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao','30')->unique();
            $table->timestamps();
        });
        DB::table('tipo_usuarios')->insert(
            array(
                'descricao' => 'Administrador'
            )
        );
        DB::table('tipo_usuarios')->insert(
            array(
                'descricao' => 'Vendas'
            )
        );
        DB::table('tipo_usuarios')->insert(
            array(
                'descricao' => 'Estoque'
            )
        );
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
            $table->integer('tipo_empresa')->unsigned()->nullable();
            $table->foreign('tipo_empresa')
                ->references('id')
                ->on('tipo_empresa');
            $table->integer('id_usuarioadm')->unsigned()->nullable();
            $table->foreign('id_usuarioadm')
                ->references('id')
                ->on('usuarios');
            $table->timestamps();
        });

        Schema::create('fornecedors', function (Blueprint $table) {
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
            $table->string('historia',200);
            $table->integer('id_usuario_adm')->unsigned()->nullable();
            $table->foreign('id_usuario_adm')
                ->references('id')
                ->on('usuarios');
            $table->timestamps();
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao');
            $table->integer('id_area')->unsigned();
            $table->foreign('id_area')
                ->references('id')
                ->on('areas');
            $table->timestamps();
        });

        DB::table('departamentos')->insert(
            array(
                'descricao' => 'Celulares',
                'id_area' => '1'
            )
        );

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
            $table->foreign('id_departamento')
                ->references('id')
                ->on('departamentos')->change();
            $table->integer('id_fornecedor')->unsigned();
            $table->foreign('id_fornecedor')
                ->references('id')
                ->on('fornecedors');
            $table->timestamps();
        });
        Schema::create('lojas', function (Blueprint $table) {
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
            $table->integer('fornecedor_id')->unsigned();
            $table->foreign('fornecedor_id')
                ->references('id')
                ->on('fornecedors');
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
