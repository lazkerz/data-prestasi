<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaUkmTable extends Migration
{
    public function up()
    {
        Schema::create('mahasiswa_ukm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('ukm_id')->constrained('ukm')->onDelete('cascade');
            $table->string('jabatan'); // Menyimpan jabatan mahasiswa dalam UKM
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswa_ukm');
    }
}
