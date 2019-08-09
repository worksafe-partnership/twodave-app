<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHazardsMethodologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hazards_methodologies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hazard_id');
            $table->foreign('hazard_id')->references('id')->on('hazards')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedInteger('methodology_id');
            $table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hazards_methodologies', function (Blueprint $table) {
            $table->dropForeign(['hazard_id']);
            $table->dropForeign(['methodology_id']);
        });
        Schema::dropIfExists('hazards_methodologies');
    }
}
