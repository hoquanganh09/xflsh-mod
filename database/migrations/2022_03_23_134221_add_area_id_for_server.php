<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaIdForServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->integer('area_id')->after('parent_id')->index();
        });

        Schema::table('server_trojan', function (Blueprint $table) {
            $table->integer('area_id')->after('parent_id')->index();
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->integer('area_id')->after('parent_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });

        Schema::table('server_trojan', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->dropColumn('area_id');
        });
    }

}