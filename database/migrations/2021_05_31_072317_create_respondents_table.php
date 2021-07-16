<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespondentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respondents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('target_id');
            $table->string('email');
            $table->string('token',100)->unique();
            $table->string('plain_token',100)->unique();
            $table->timestamp('start_working_at')->nullable();
            $table->timestamp('submited_at')->nullable();
            $table->timestamps();
            $table->foreign('target_id')->references('id')->on('targets')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('respondents');
    }
}
