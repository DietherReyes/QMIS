<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerSatisfactionDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('customer_satisfaction_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->integer('csm_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('customer_satisfaction_documents', function($table) {
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
        Schema::dropIfExists('customer_satisfaction_documents');
    }
}
