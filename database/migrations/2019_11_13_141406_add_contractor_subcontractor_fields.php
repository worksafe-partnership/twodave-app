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
            $table->unsignedInteger('company_logo_id')->nullable();
            $table->foreign('company_logo_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('CASCADE');
        });

        Schema::create('project_subcontractors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('billable')->nullable();
        });

        Schema::create('vtram_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vtrams_id');
            $table->foreign('vtrams_id')->references('id')->on('vtrams')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::table('vtram_users', function ($table) {
            $table->dropForeign(['vtrams_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::drop('vtram_users');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('billable');
        });

        Schema::table('project_subcontractors', function ($table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['company_id']);
        });

        Schema::drop('project_subcontractors');

        Schema::table('vtrams', function ($table) {
            $table->dropForeign(['company_logo_id']);
            $table->dropColumn('company_logo_id');
            $table->dropColumn('general_rams');
        });
    }
}
