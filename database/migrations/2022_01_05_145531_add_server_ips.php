<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerIps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_trojan', function (Blueprint $table) {
            $table->string('ips')->after('tags')->nullable()->default(null);;
        });
        Schema::table('server_vmess', function (Blueprint $table) {
            $table->string('ips')->after('tags')->nullable()->default(null);;
        });
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->string('ips')->after('tags')->nullable()->default(null);
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
            $table->dropColumn('ips');
        });
        Schema::table('server_vmess', function (Blueprint $table) {
            $table->dropColumn('ips');
        });
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->dropColumn('ips');
        });
    }
}