<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_trojan', function (Blueprint $table) {
            $table->integer('check')->after('show')->default(0)->index();
        });
        Schema::table('server_vmess', function (Blueprint $table) {
            $table->integer('check')->after('show')->default(0)->index();
        });
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->integer('check')->after('show')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_trojan', function (Blueprint $table) {
            $table->dropColumn('check');
        });
        Schema::table('server_vmess', function (Blueprint $table) {
            $table->dropColumn('check');
        });
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->dropColumn('check');
        });
    }
}