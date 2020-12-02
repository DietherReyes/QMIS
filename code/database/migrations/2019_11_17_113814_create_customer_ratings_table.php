<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('five_star')->nullable();
            $table->integer('four_star')->nullable();
            $table->integer('three_below')->nullable();
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('customer_ratings', function($table) {
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
        Schema::dropIfExists('customer_ratings');
    }
}
