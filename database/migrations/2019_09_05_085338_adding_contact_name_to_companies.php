<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingContactNameToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->string('contact_name');
            $table->dropColumn('fax');
        });
        Schema::table('table_rows', function ($table) {
            $table->text('col_5')->nullable();
            $table->text('col_1')->nullable()->change();
            $table->text('col_2')->nullable()->change();
            $table->text('col_3')->nullable()->change();
            $table->text('col_4')->nullable()->change();
        });
        Schema::table('methodologies', function ($table) {
            $table->boolean('tickbox_answer')->default(1);
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
            $table->dropColumn('contact_name');
            $table->string('fax');
        });
        Schema::table('table_rows', function ($table) {
            $table->dropColumn('col_5')->nullable();
            $table->string('col_1')->nullable()->change();
            $table->string('col_2')->nullable()->change();
            $table->string('col_3')->nullable()->change();
            $table->string('col_4')->nullable()->change();
        });
        Schema::table('methodologies', function ($table) {
            $table->dropColumn('tickbox_answer');
        });
    }
}
