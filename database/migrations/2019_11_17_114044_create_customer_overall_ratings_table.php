<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOverallRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_overall_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('response_delivery');
            $table->decimal('work_quality');
            $table->decimal('personnels_quality');
            $table->decimal('overall_rating');
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('customer_overall_ratings', function($table) {
            $table->foreign('csm_id')->references('id')->on('customer_satisfaction_measurements')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_overall_ratings');
    }
}
