<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficerNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officer_notes', function (Blueprint $table) {
            $table->id(); 
            $table->enum('type', ['note', 'photo','pdf','location','ipaddr']);
            $table->string('value', 255);
            $table->unsignedBigInteger(('officer_target_id'));
            $table->unsignedBigInteger('officer_id');
            $table->unsignedBigInteger('target_id');
            $table->timestamps();

            $table->foreign('officer_target_id')->references('id')->on('officer_targets')->cascadeOnDelete();
            $table->foreign('officer_id')->references('id')->on('officers')->cascadeOnDelete();
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
        Schema::dropIfExists('officer_notes');
    }
}
