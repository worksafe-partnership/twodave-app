<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentUploadFieldsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('allow_file_uploads')->nullable();
        });

        Schema::table('vtrams', function ($table) {
            $table->boolean('vtram_is_file')->nullable();
            $table->integer('vtram_file')->nullable()->unsigned();
            $table->foreign('vtram_file')->references('id')->on('files')->onDelete('CASCADE')->onUpdate('CASCADE');
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
            $table->dropForeign(['vtram_file']);
            $table->dropColumn('vtram_file');
            $table->dropColumn('vtram_is_file');
        });

        Schema::table('companies', function ($table) {
            $table->dropColumn('allow_file_uploads');
        });
    }
}
