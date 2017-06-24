<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColunsUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign('usuarios_id_fornecedor_foreign');
            $table->dropForeign('usuarios_id_loja_foreign');
            $table->dropColumn('id_fornecedor');
            $table->dropColumn('id_loja');
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
