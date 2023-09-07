<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePricesForPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan', function (Blueprint $table) {
            $table->text('prices')->after('allow_ids')->nullable();
            $table->dropColumn('month_price');
            $table->dropColumn('quarter_price');
            $table->dropColumn('half_year_price');
            $table->dropColumn('year_price');
            $table->dropColumn('two_year_price');
            $table->dropColumn('three_year_price');
            $table->dropColumn('onetime_price');
            $table->dropColumn('reset_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::table('plan', function (Blueprint $table) {
            $table->dropColumn('prices');
            $table->integer('month_price')->after('allow_ids')->nullable();
            $table->integer('quarter_price')->after('allow_ids')->nullable();
            $table->integer('half_year_price')->after('allow_ids')->nullable();
            $table->integer('year_price')->after('allow_ids')->nullable();
            $table->integer('two_year_price')->after('allow_ids')->nullable();
            $table->integer('three_year_price')->after('allow_ids')->nullable();
            $table->integer('onetime_price')->after('allow_ids')->nullable();
            $table->integer('reset_price')->after('allow_ids')->nullable();
        });

    }
}