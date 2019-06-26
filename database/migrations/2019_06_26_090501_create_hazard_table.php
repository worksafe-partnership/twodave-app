<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHazardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->string('entity');
            $table->integer('entity_id');
            $table->text('control');
            $table->char('risk');
            $table->char('r_risk');
            $table->integer('list_order')->nullable();
            $table->string('at_risk');
            $table->string('other_at_risk');
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
        Schema::drop('hazards');
    }
}
