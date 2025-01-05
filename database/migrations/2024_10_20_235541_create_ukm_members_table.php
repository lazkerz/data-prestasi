<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUkmMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ukm_members', function (Blueprint $table) {
            $table->id(); // Primary key with AUTO_INCREMENT
            $table->unsignedBigInteger('ukm_id'); // Foreign key to ukm table
            $table->unsignedBigInteger('mahasiswa_id'); // Foreign key to mahasiswa table
            $table->string('position'); // Position of the member in UKM
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('ukm_id')
                  ->references('id')
                  ->on('ukm')
                  ->onDelete('cascade');

            $table->foreign('mahasiswa_id')
                  ->references('id')
                  ->on('mahasiswa')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ukm_members');
    }
}
