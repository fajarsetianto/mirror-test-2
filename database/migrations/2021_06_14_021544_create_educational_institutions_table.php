<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationalInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educational_institutions', function (Blueprint $table) {
            $table->id();
            $table->uuid('sp_id');
            $table->text('name');
            $table->string('npsn');
            $table->string('bp');
            $table->unsignedBigInteger('bp_id');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('province');
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('nama_dusun')->nullable();
            $table->unsignedBigInteger('kode_desa')->nullable();
            $table->string('nama_desa')->nullable();
            $table->string('nama_kecamatan')->nullable();
            $table->unsignedBigInteger('kode_kecamatan')->nullable();
            $table->string('city');
            $table->unsignedBigInteger('kode_kabupaten')->nullable();
            $table->unsignedBigInteger('kode_provinsi')->nullable();
            $table->string('headmaster')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('status_sekolah')->nullable();
        
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
        Schema::dropIfExists('educational_institutions');
    }
}
