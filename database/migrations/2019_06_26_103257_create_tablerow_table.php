<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_rows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('col_1')->nullable();
            $table->string('col_2')->nullable();
            $table->string('col_3')->nullable();
            $table->string('col_4')->nullable();
            $table->integer('list_order')->nullable();
            $table->integer('methodology_id')->unsigned();
            $table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('cols_filled')->nullable();
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
        Schema::drop('table_rows');
    }
}
