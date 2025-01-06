<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmpsMembersTable extends Migration
{
    public function up()
    {
        Schema::create('hmps_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hmps_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('position');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('hmps_id')
                  ->references('id')
                  ->on('hmps')
                  ->onDelete('cascade');

            $table->foreign('mahasiswa_id')
                  ->references('id')
                  ->on('mahasiswa')
                  ->onDelete('cascade');

            // Ensure a student can only have one position in an HMPS
            $table->unique(['hmps_id', 'mahasiswa_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hmps_members');
    }
}
