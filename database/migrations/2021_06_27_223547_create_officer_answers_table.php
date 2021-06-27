<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficerAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officer_answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->unsignedBigInteger(('offered_answer_id'));
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('target_id');
            $table->unsignedBigInteger('respondent_id');
            $table->timestamps();

            $table->foreign('offered_answer_id')->references('id')->on('offered_answers')->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnDelete();
            $table->foreign('target_id')->references('id')->on('targets')->cascadeOnDelete();
            $table->foreign('officer_id')->references('id')->on('officers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officer_answers');
    }
}
