<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSatisfactionMeasurementSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_satisfaction_measurement_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('functional_unit')->nullable();
            $table->string('year')->nullable();
            $table->integer('total_customer')->nullable();
            $table->decimal('q1_overall_rating')->nullable();
            $table->decimal('q2_overall_rating')->nullable();
            $table->decimal('q3_overall_rating')->nullable();
            $table->decimal('q4_overall_rating')->nullable();
            $table->decimal('response_delivery')->nullable();
            $table->decimal('work_quality')->nullable();
            $table->decimal('personnels_quality')->nullable();
            $table->decimal('overall_rating')->nullable();
            $table->string('adjectival_rating')->nullable();
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
        Schema::dropIfExists('customer_satisfaction_measurement_summaries');
    }
}
