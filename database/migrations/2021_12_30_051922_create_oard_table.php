<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code')->index('code');
            $table->string('name');
            $table->boolean('type');
            $table->integer('value');
            $table->integer('limit_use')->nullable();
            $table->integer('limit_use_with_user')->nullable();
            $table->string('limit_plan_ids')->nullable();
            $table->string('limit_cycle', 100)->nullable();
            $table->integer('started_at');
            $table->integer('ended_at');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('invite_code', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->char('code', 32);
            $table->boolean('status')->default(false);
            $table->integer('pv')->default(0);
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['user_id', 'status'], 'user_id_status');
        });

        Schema::create('knowledge', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('language', 5)->comment('語言');
            $table->string('category')->comment('分類名');
            $table->string('title')->comment('標題');
            $table->text('body')->comment('內容');
            $table->integer('sort')->nullable()->comment('排序');
            $table->boolean('show')->default(false)->comment('顯示');
            $table->boolean('free')->default(true)->comment('是否免费');
            $table->integer('created_at')->comment('創建時間');
            $table->integer('updated_at')->comment('更新時間');

            $table->index(['language', 'show'], 'language_show');
        });

        Schema::create('mail_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email', 64);
            $table->string('subject');
            $table->string('template_name');
            $table->text('error')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('notice', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->text('content');
            $table->string('img_url')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('order', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invite_user_id')->nullable()->default(0)->index('invite_user_id');
            $table->integer('user_id')->index('user_id');
            $table->integer('plan_id');
            $table->integer('coupon_id')->nullable()->comment('0');
            $table->integer('payment_id')->nullable()->default(0);
            $table->integer('type')->comment('1新购2续费3升级');
            $table->string('cycle');
            $table->string('trade_no', 36)->index('trade_no');
            $table->string('callback_no')->nullable();
            $table->integer('total_amount');
            $table->integer('discount_amount')->nullable();
            $table->integer('surplus_amount')->nullable()->comment('剩余价值');
            $table->integer('refund_amount')->nullable()->comment('退款金额');
            $table->integer('balance_amount')->nullable()->comment('使用余额');
            $table->text('surplus_order_ids')->nullable()->comment('折抵订单');
            $table->boolean('status')->default(false)->index('status')->comment('0待支付1开通中2已取消3已完成4已折抵');
            $table->boolean('commission_status')->default(false)->comment('0待确认1发放中2有效3无效');
            $table->integer('commission_balance')->default(0);
            $table->integer('paid_at')->nullable();
            $table->integer('created_at')->index('created_at');
            $table->integer('updated_at');

            $table->index(['user_id', 'status'], 'status_user_id');
            $table->index(['status', 'created_at'], 'created_at_status');
        });

        Schema::create('order_stat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_count')->comment('订单数量');
            $table->integer('order_amount')->comment('订单合计');
            $table->integer('commission_count');
            $table->integer('commission_amount')->comment('佣金合计');
            $table->char('record_type', 1)->index('record_type');
            $table->integer('record_at')->unique('record_at');
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['record_type', 'record_at'], 'record_at_record_type');
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('uuid', 32)->index('uuid');
            $table->string('payment', 16);
            $table->string('name');
            $table->text('config');
            $table->boolean('enable')->default(false);
            $table->integer('sort')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('plan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('group_id');
            $table->integer('transfer_enable');
            $table->string('name');
            $table->boolean('show')->default(false);
            $table->integer('sort')->nullable();
            $table->boolean('renew')->default(true);
            $table->text('content')->nullable();
            $table->integer('month_price')->nullable();
            $table->integer('quarter_price')->nullable();
            $table->integer('half_year_price')->nullable();
            $table->integer('year_price')->nullable();
            $table->integer('two_year_price')->nullable();
            $table->integer('three_year_price')->nullable();
            $table->integer('onetime_price')->nullable();
            $table->integer('reset_price')->nullable();
            $table->boolean('reset_traffic_method')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('server', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('group_id');
            $table->string('name');
            $table->integer('parent_id')->nullable()->default(0)->index('parent_id');
            $table->string('host');
            $table->integer('port');
            $table->integer('server_port');
            $table->string('tags')->nullable();
            $table->string('rate', 11);
            $table->text('network');
            $table->tinyInteger('tls')->default(0);
            $table->integer('alter_id')->default(1);
            $table->text('network_settings')->nullable();
            $table->text('tls_settings')->nullable();
            $table->text('rule_settings')->nullable();
            $table->text('dns_settings')->nullable();
            $table->boolean('show')->default(false)->index('show');
            $table->integer('sort')->nullable()->default(0);
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('server_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('server_shadowsocks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('group_id');
            $table->integer('parent_id')->nullable()->default(0)->index('parent_id');
            $table->string('tags')->nullable();
            $table->string('name');
            $table->string('rate', 11);
            $table->string('host');
            $table->integer('port');
            $table->integer('server_port');
            $table->string('cipher');
            $table->tinyInteger('show')->default(0)->index('show');
            $table->integer('sort')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('server_trojan', function (Blueprint $table) {
            $table->integer('id', true)->comment('节点ID');
            $table->string('group_id')->comment('节点组');
            $table->integer('parent_id')->nullable()->default(0)->index('parent_id')->comment('父节点');
            $table->string('tags')->nullable()->comment('节点标签');
            $table->string('name')->comment('节点名称');
            $table->string('rate', 11)->comment('倍率');
            $table->string('host')->comment('主机名');
            $table->integer('port')->comment('连接端口');
            $table->integer('server_port')->comment('服务端口');
            $table->boolean('allow_insecure')->default(false)->comment('是否允许不安全');
            $table->string('server_name')->nullable();
            $table->boolean('show')->default(false)->index('show')->comment('是否显示');
            $table->integer('sort')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('ticket', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('last_reply_user_id');
            $table->string('subject');
            $table->boolean('level');
            $table->boolean('status')->default(false)->index('status')->comment('0:已开启 1:已关闭');
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['user_id', 'created_at'], 'user_id_creatd_at');
        });

        Schema::create('ticket_message', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('ticket_id')->index('ticket_id');
            $table->text('message');
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('traffic_server_log', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('server_id')->index('server_id');
            $table->string('server_type', 20);
            $table->string('u');
            $table->string('d');
            $table->date('log_date');
            $table->integer('log_at')->index('log_at');
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['log_at', 'server_id'], 'server_id_log_at');
        });

        Schema::create('traffic_user_log', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('u');
            $table->string('d');
            $table->date('log_date');
            $table->integer('log_at')->index('log_at');
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['log_at', 'user_id'], 'user_id_log_at');
        });

        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invite_user_id')->nullable()->default(0);
            $table->bigInteger('telegram_id')->nullable()->default(0)->index('telegram_id');
            $table->string('email', 64)->unique('email');
            $table->string('password', 64);
            $table->char('password_algo', 10)->nullable();
            $table->char('password_salt', 10)->nullable();
            $table->integer('balance')->nullable()->default(0);
            $table->tinyInteger('commission_type')->default(0)->comment('0: system 1: cycle 2: onetime');
            $table->integer('discount')->nullable();
            $table->integer('commission_rate')->nullable();
            $table->integer('commission_balance')->nullable()->default(0);
            $table->integer('t')->nullable()->default(0);
            $table->bigInteger('u')->nullable()->default(0);
            $table->bigInteger('d')->nullable()->default(0);
            $table->bigInteger('transfer_enable')->default(0);
            $table->integer('last_checkin_at')->default(0);
            $table->boolean('banned')->default(false);
            $table->boolean('is_admin')->nullable()->default(false);
            $table->integer('last_login_at')->nullable();
            $table->boolean('is_staff')->nullable()->default(false);
            $table->integer('last_login_ip')->nullable();
            $table->string('uuid', 36);
            $table->integer('group_id')->nullable()->default(0)->index('group_id');
            $table->integer('plan_id')->nullable()->default(0)->index('plan_id');
            $table->tinyInteger('remind_expire')->nullable()->default(1);
            $table->tinyInteger('remind_traffic')->nullable()->default(1);
            $table->char('token', 32)->index('token');
            $table->bigInteger('expired_at')->nullable()->index('expired_at');
            $table->text('remarks')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');

            $table->index(['password', 'email'], 'password_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');

        Schema::dropIfExists('traffic_user_log');

        Schema::dropIfExists('traffic_server_log');

        Schema::dropIfExists('ticket_message');

        Schema::dropIfExists('ticket');

        Schema::dropIfExists('server_trojan');

        Schema::dropIfExists('server_shadowsocks');

        Schema::dropIfExists('server_group');

        Schema::dropIfExists('server');

        Schema::dropIfExists('plan');

        Schema::dropIfExists('payment');

        Schema::dropIfExists('order_stat');

        Schema::dropIfExists('order');

        Schema::dropIfExists('notice');

        Schema::dropIfExists('mail_log');

        Schema::dropIfExists('knowledge');

        Schema::dropIfExists('invite_code');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('coupon');
    }
}