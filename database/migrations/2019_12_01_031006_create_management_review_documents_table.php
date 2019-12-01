<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementReviewDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_review_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('type');
            $table->integer('manrev_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('management_review_documents', function($table) {
            $table->foreign('manrev_id')->references('id')->on('management_reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('management_review_documents');
    }
}
