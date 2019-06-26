<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable()->unsigned();
            $table->string('name');
            $table->text('description');
            $table->integer('logo')->nullable()->unsigned();
            $table->foreign('logo')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('reference');
            $table->text('key_points');
            $table->integer('havs_noise_assessment')->nullable()->unsigned();
            $table->foreign('havs_noise_assessment')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->integer('coshh_assessment')->nullable()->unsigned();
            $table->foreign('coshh_assessment')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->date('review_due')->nullable();
            $table->date('approved_date')->nullable();
            $table->integer('original_id')->nullable();
            $table->integer('revision_number')->nullable();
            $table->string('status')->default('NEW');
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->integer('submitted_by')->nullable()->unsigned();
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->integer('approved_by')->nullable()->unsigned();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->date('date_replaced')->nullable();
            $table->date('resubmit_by')->nullable();
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
        Schema::drop('templates');
    }
}
