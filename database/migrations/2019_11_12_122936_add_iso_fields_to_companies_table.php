<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsoFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('show_document_ref_on_pdf')->nullable();
            $table->boolean('show_message_on_pdf')->nullable();
            $table->text('message');
            $table->boolean('show_revision_no_on_pdf')->nullable();
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
            $table->dropColumn('show_document_ref_on_pdf');
            $table->dropColumn('show_message_on_pdf');
            $table->dropColumn('message');
            $table->dropColumn('show_revision_no_on_pdf');
        });
    }
}
