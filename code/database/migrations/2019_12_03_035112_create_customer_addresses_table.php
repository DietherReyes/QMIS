<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->integer('count');
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('customer_addresses', function($table) {
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
        Schema::dropIfExists('customer_addresses');
    }
}
