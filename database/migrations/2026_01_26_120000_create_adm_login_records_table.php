<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmLoginRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('adm_login_records')) {
            Schema::create('adm_login_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario');
                $table->timestamp('login')->nullable();
                $table->timestamp('logout')->nullable();
                $table->timestamps();

                // $table->foreign('usuario')->references('id')->on('adm_usuario');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm_login_records');
    }
}
