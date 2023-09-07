<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeLimitForPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan', function (Blueprint $table) {
            $table->tinyInteger('time_limit')->after('content')->default(0);
            $table->integer('start_sec')->after('content')->nullable();
            $table->integer('end_sec')->after('content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan', function (Blueprint $table) {
            $table->dropColumn('time_limit');
            $table->dropColumn('start_sec');
            $table->dropColumn('end_sec');
        });
    }
}