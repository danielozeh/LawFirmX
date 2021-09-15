<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->string('case_number');
            $table->foreignId('case_type_id')->constrained('case_types');
            $table->string('stage_of_case');
            $table->string('act');
            $table->string('filing_number');
            $table->string('registration_number');
            $table->date('registration_date');
            $table->date('first_hearing_date');
            $table->integer('priority')->default(1); //1 for hight, 2 for medium, 3 for low
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
        Schema::dropIfExists('case_details');
    }
}
