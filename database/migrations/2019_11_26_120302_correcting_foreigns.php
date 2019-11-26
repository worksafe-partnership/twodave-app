<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectingForeigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vtrams', function ($table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
        });
        Schema::table('templates', function ($table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vtrams', function ($table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
        Schema::table('templates', function ($table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
        });
    }
}
