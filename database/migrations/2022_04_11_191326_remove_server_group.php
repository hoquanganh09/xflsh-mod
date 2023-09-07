<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveServerGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('server_group');
        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->json('plan_id')->after('id');
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->json('plan_id')->after('id');
        });

        Schema::table('server_trojan', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->json('plan_id')->after('id');
        });

        Schema::table('plan', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('server_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::table('server_vmess', function (Blueprint $table) {
            $table->string('group_id')->after('id');
            $table->dropColumn('plan_id');
        });

        Schema::table('server_shadowsocks', function (Blueprint $table) {
            $table->string('group_id')->after('id');
            $table->dropColumn('plan_id');

        });

        Schema::table('server_trojan', function (Blueprint $table) {
            $table->string('group_id')->after('id');
            $table->dropColumn('plan_id');
        });

        Schema::table('plan', function (Blueprint $table) {
            $table->integer('group_id')->after('id');
        });
    }
}