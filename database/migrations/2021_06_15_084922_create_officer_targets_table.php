<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficerTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officer_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->unsignedBigInteger('target_id');
            $table->date('submited_at')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->timestamps();

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
        Schema::dropIfExists('officer_targets');
    }
}
