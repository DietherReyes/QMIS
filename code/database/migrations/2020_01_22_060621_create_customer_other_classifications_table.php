<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOtherClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::create('customer_other_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('count')->nullable();
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        
        Schema::table('customer_other_classifications', function($table) {
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
        Schema::dropIfExists('customer_other_classifications');
    }
}
