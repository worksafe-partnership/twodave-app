<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVtramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vtrams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('logo')->nullable()->unsigned();
            $table->foreign('logo')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('reference');
            $table->text('key_points')->nullable();
            $table->integer('havs_noise_assessment')->nullable()->unsigned();
            $table->foreign('havs_noise_assessment')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('coshh_assessment')->nullable()->unsigned();
            $table->foreign('coshh_assessment')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->date('review_due')->nullable();
            $table->date('approved_date')->nullable();
            $table->integer('original_id')->nullable()->unsigned();
            $table->foreign('original_id')->references('id')->on('vtrams')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('revision_number')->nullable();
            $table->string('status')->default('NEW');
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->integer('submitted_by')->nullable()->unsigned();
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->date('submitted_date')->nullable();
            $table->integer('approved_by')->nullable()->unsigned();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->date('date_replaced')->nullable();
            $table->date('resubmit_by')->nullable();
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
            $table->boolean('dynamic_risk')->nullable();
            $table->integer('pdf')->nullable()->unsigned();
            $table->foreign('pdf')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('pages_in_pdf')->nullable();
            $table->string('created_from_entity')->nullable();
            $table->integer('created_from_id')->nullable()->unsigned();
            $table->boolean('show_responsible_person')->nullable();
            $table->string('responsible_person')->nullable();
            $table->boolean('client_on_pdf')->nullable();
            $table->boolean('pc_on_pdf')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vtrams');
    }
}
