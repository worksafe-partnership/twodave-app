<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrincipalContractorFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('is_principal_contractor')->nullable();
        });

        Schema::table('project_subcontractors', function ($table) {
            $table->string('contractor_or_sub', 20)->default('SUBCONTRACTOR');
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
            $table->dropColumn('is_principal_contractor');
        });

        Schema::table('project_subcontractors', function ($table) {
            $table->dropColumn('contractor_or_sub');
        });
    }
}
