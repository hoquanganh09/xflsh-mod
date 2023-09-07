<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIpsTypeForServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_trojan', function (Blueprint $table) {
            $table->text('ips')->change();
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->text('ips')->change();
        });

        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->text('ips')->change();
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
            $table->string('ips')->change();
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->string('ips')->change();
        });

        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->string('ips')->change();
        });
    }
}