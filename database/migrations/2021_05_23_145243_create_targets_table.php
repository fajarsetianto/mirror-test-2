<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['responden', 'petugas MONEV', 'responden & petugas MONEV' ])->default('responden');
            $table->unsignedBigInteger('officer_id')->nullable();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();

            $table->timestamps();

            $table->foreign('officer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            $table->foreign('institution_id')->references('id')->on('institutions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
