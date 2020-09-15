<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingBasicSubsFunctionality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->integer('num_vtrams')->nullable()->default(null);
            $table->string('sub_frequency', 10)->nullable()->default(null);
            $table->string('start_date')->nullable()->default(null);
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
            $table->dropColumn('num_vtrams');
            $table->dropColumn('sub_frequency');
            $table->dropColumn('start_date');
        });
    }
}
