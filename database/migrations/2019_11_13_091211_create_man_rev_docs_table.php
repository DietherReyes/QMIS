<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManRevDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_rev_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->integer('manrev_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('man_rev_docs', function($table) {
            $table->foreign('manrev_id')->references('id')->on('management_reviews');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('man_rev_docs');
    }
}
