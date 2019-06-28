<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('review_timescale');
            $table->string('vtrams_name', 100);
            $table->string('email');
            $table->string('phone');
            $table->string('fax');
            $table->char('low_risk_character')->default('L');
            $table->char('med_risk_character')->default('M');
            $table->char('high_risk_character')->default('H');
            $table->char('no_risk_character');
            $table->string('primary_colour');
            $table->string('secondary_colour');
            $table->boolean('light_text')->nullable();
            $table->string('accept_label');
            $table->string('amend_label');
            $table->string('reject_label');
            $table->integer('logo')->nullable()->unsigned();
            $table->foreign('logo')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->nullable()->before('email');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
        Schema::drop('companies');
    }
}
