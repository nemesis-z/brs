<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alogs', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('admin_id')->unsigned();
            $table->boolean('is_error')->default(0);
            $table->string('a',32);
            $table->string('b',32)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alogs');
    }
}
