<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBriefingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('briefings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('vtram_id')->unsigned();
            $table->foreign('vtram_id')->references('id')->on('vtrams')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('briefed_by');
            $table->string('name');
            $table->text('notes')->nullable();
            $table->softDeletes();
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
        Schema::drop('briefings');
    }
}
