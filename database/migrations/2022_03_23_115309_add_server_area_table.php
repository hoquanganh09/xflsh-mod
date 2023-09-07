<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServerAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_area', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('flag', 30);
            $table->string('country', 30);
            $table->string('country_code', 10)->index();
            $table->string('city', 30);
            $table->float('lng');
            $table->float('lat');
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('server_area');
    }
}