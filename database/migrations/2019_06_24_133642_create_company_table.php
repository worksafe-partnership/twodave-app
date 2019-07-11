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
            $table->text('main_description')->nullable();
            $table->text('post_risk_assessment_text')->nullable();
            $table->text('task_description')->nullable();
            $table->text('plant_and_equipment')->nullable();
            $table->text('disposing_of_waste')->nullable();
            $table->text('first_aid')->nullable();
            $table->text('noise')->nullable();
            $table->text('working_at_height')->nullable();
            $table->text('manual_handling')->nullable();
            $table->text('accident_reporting')->nullable();
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
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
        Schema::drop('companies');
    }
}
