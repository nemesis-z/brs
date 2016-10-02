<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jdates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tgl_id')->unsigned();
            $table->foreign('tgl_id')->references('id')->on('tgls')->onDelete('cascade');
            $table->integer('zz');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jdates');
    }
}
