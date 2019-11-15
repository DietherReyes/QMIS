<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagementReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meeting_name');
            $table->string('venue');
            $table->date('date');
            $table->string('minutes');
            $table->string('action_plan');
            $table->string('attendance');
            $table->string('agenda_memo');
            $table->string('presentation_slide');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('management_reviews');
    }
}
