<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualityManualDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_manual_documentations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_code');
            $table->integer('revision_number');
            $table->integer('page_number');
            $table->date('effectivity_date');
            $table->string('section');
            $table->string('subject');
            $table->string('quality_manual_doc');
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
        Schema::dropIfExists('quality_manual_documentations');
    }
}
