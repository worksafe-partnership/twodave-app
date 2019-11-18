<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCompanyFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('task_description');
            $table->dropColumn('plant_and_equipment');
            $table->dropColumn('disposing_of_waste');
            $table->dropColumn('first_aid');
            $table->dropColumn('noise');
            $table->dropColumn('working_at_height');
            $table->dropColumn('manual_handling');
            $table->dropColumn('accident_reporting');
        });
        Schema::table('vtrams', function ($table) {
            $table->dropColumn('task_description');
            $table->dropColumn('plant_and_equipment');
            $table->dropColumn('disposing_of_waste');
            $table->dropColumn('first_aid');
            $table->dropColumn('noise');
            $table->dropColumn('working_at_height');
            $table->dropColumn('manual_handling');
            $table->dropColumn('accident_reporting');
        });
        Schema::table('templates', function ($table) {
            $table->dropColumn('task_description');
            $table->dropColumn('plant_and_equipment');
            $table->dropColumn('disposing_of_waste');
            $table->dropColumn('first_aid');
            $table->dropColumn('noise');
            $table->dropColumn('working_at_height');
            $table->dropColumn('manual_handling');
            $table->dropColumn('accident_reporting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->text('task_description')->nullable();
            $table->text('plant_and_equipment')->nullable();
            $table->text('disposing_of_waste')->nullable();
            $table->text('first_aid')->nullable();
            $table->text('noise')->nullable();
            $table->text('working_at_height')->nullable();
            $table->text('manual_handling')->nullable();
            $table->text('accident_reporting')->nullable();
        });
        Schema::table('vtrams', function ($table) {
            $table->text('task_description')->nullable();
            $table->text('plant_and_equipment')->nullable();
            $table->text('disposing_of_waste')->nullable();
            $table->text('first_aid')->nullable();
            $table->text('noise')->nullable();
            $table->text('working_at_height')->nullable();
            $table->text('manual_handling')->nullable();
            $table->text('accident_reporting')->nullable();
        });
        Schema::table('templates', function ($table) {
            $table->text('task_description')->nullable();
            $table->text('plant_and_equipment')->nullable();
            $table->text('disposing_of_waste')->nullable();
            $table->text('first_aid')->nullable();
            $table->text('noise')->nullable();
            $table->text('working_at_height')->nullable();
            $table->text('manual_handling')->nullable();
            $table->text('accident_reporting')->nullable();
        });
    }
}
