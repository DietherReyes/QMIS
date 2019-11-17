<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSatisfactionMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_satisfaction_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('functional_unit');
            $table->string('year');
            $table->integer('quarter');
            $table->integer('total_customer');
            $table->integer('total_male');
            $table->integer('total_female');
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('customer_satisfaction_measurements');
    }
}
