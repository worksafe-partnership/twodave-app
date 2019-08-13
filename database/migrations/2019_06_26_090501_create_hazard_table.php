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
            $table->integer('risk')->nullable();
            $table->integer('risk_probability')->nullable();
            $table->integer('risk_severity')->nullable();
            $table->integer('r_risk')->nullable();
            $table->integer('r_risk_probability')->nullable();
            $table->integer('r_risk_severity')->nullable();
            $table->integer('list_order')->nullable();
            $table->jsonb('at_risk')->nullable();
            $table->string('other_at_risk')->nullable();
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
