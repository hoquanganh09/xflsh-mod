<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworkForServerTrojan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('server_trojan', function (Blueprint $table) {
            $table->string('network')->nullable()->after('server_name');
            $table->text('network_settings')->nullable()->after('network');
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
            $table->dropColumn('network');
            $table->dropColumn('network_settings');
        });
    }
}