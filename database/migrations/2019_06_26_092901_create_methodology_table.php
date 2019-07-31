<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMethodologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('methodologies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->text('title')->nullable();
            $table->string('entity');
            $table->integer('entity_id');
            $table->text('text_before')->nullable();
            $table->text('text_after')->nullable();
            $table->integer('image')->nullable()->unsigned();
            $table->foreign('image')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('image_on', 5)->nullable();
            $table->integer('list_order')->nullable()->unsigned();
            $table->text('icon_main_heading')->nullable();
            $table->text('icon_sub_heading')->nullable();
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
        Schema::drop('methodologies');
    }
}
