<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpUserAgentToAdmLoginRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adm_login_records', function (Blueprint $table) {
            $table->string('ip', 45)->nullable()->after('usuario');
            $table->string('user_agent', 255)->nullable()->after('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adm_login_records', function (Blueprint $table) {
            $table->dropColumn(['ip', 'user_agent']);
        });
    }
}
