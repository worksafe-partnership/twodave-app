<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractorSubcontractorFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vtrams', function ($table) {
            $table->boolean('general_rams')->nullable();
        });

        Schema::create('project_subcontractors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('projects')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::table('project_subcontractors', function ($table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['company_id']);
        });

        Schema::drop('project_subcontractors');

        Schema::table('vtrams', function ($table) {
            $table->dropColumn('general_rams');
        });
    }
}
