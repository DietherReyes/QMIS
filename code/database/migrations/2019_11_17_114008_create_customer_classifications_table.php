<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student')->nullable();
            $table->integer('government_employee')->nullable();
            $table->integer('internal')->nullable();
            $table->integer('business')->nullable();
            $table->integer('homemaker')->nullable();
            $table->integer('entrepreneur')->nullable();
            $table->integer('private_organization')->nullable();
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('customer_classifications', function($table) {
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
        Schema::dropIfExists('customer_classifications');
    }
}
