<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->string('label')->nullable();
            $table->boolean('heading')->nullable();
            $table->integer('list_order')->nullable()->unsigned();
            $table->integer('image')->nullable()->unsigned();
            $table->foreign('image')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('methodology_id')->unsigned();
            $table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::drop('instructions');
    }
}
