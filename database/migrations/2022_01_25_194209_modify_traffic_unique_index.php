<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTrafficUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traffic_server_log', function (Blueprint $table) {
            $table->dropIndex('server_id_log_at');
            $table->unique(['server_id', 'log_at'], 'server_id_log_at_unique');
        });

        Schema::table('traffic_user_log', function (Blueprint $table) {
            $table->dropIndex('user_id_log_at');
            $table->unique(['user_id', 'log_at'], 'user_id_log_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traffic_server_log', function (Blueprint $table) {
            $table->dropUnique('server_id_log_at_unique');
            $table->index(['server_id', 'log_at'], 'server_id_log_at');
        });

        Schema::table('traffic_user_log', function (Blueprint $table) {
            $table->dropUnique('user_id_log_at_unique');
            $table->index(['user_id', 'log_at'], 'user_id_log_at');
        });
    }

//select count(*) from v2_traffic_user_log where log_date = '2022-01-25'
}