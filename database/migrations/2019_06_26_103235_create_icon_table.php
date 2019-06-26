<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('text')->nullable();
            $table->integer('list_order')->nullable();
            $table->integer('methodology_id')->unsigned();
            $table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('type');
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
        Schema::drop('icons');
    }
}
