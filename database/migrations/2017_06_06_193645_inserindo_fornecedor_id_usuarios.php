<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InserindoFornecedorIdUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('id_fornecedor')
                ->references('id')
                ->on('fornecedores')->change();
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
