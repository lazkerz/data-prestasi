<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmpsTable extends Migration
{
    public function up()
    {
        Schema::create('hmps', function (Blueprint $table) {
            $table->id();
            $table->string('nama');  // Will be same as prodi name
            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->unsignedBigInteger('prodi_id'); // Foreign key to prodis table
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('prodi_id')
                  ->references('id')
                  ->on('prodis')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hmps');
    }
}
