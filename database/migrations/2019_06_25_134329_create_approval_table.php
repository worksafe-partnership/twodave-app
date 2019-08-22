<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity');
            $table->integer('entity_id')->unsigned();
            $table->text('comment')->nullable();
            $table->string('type');
            $table->string('completed_by');
            $table->integer('completed_by_id')->nullable()->unsigned();
            $table->foreign('completed_by_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->date('approved_date');
            $table->date('resubmit_date')->nullable();
            $table->string('status_at_time');
            $table->integer('review_document')->nullable()->unsigned();
            $table->foreign('review_document')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::drop('approvals');
    }
}
