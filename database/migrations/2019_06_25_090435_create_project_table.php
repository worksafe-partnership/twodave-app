<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ref');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('project_admin')->nullable()->unsigned();
            $table->foreign('project_admin')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->boolean('principle_contractor');
            $table->string('principle_contractor_name');
            $table->string('principle_contractor_email');
            $table->string('client_name');
            $table->integer('review_timescale');
            $table->boolean('show_contact');
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
        Schema::drop('projects');
    }
}
