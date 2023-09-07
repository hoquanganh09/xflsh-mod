<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOrderSurplusFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('surplus_amount');
            $table->dropColumn('refund_amount');
            $table->dropColumn('surplus_order_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('surplus_amount')->nullable()->comment('剩余价值');
            $table->integer('refund_amount')->nullable()->comment('退款金额');
            $table->text('surplus_order_ids')->nullable()->comment('折抵订单');
        });
    }
}