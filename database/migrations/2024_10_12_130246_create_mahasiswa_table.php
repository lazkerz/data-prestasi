<?php
// database/migrations/xxxx_xx_xx_create_mahasiswa_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim')->unique();
            $table->enum('jenis_kelamin', ['L', 'P']); // L for male, P for female
            $table->string('prodi');
            $table->string('jenjang');
            $table->string('agama');
            $table->year('angkatan')->default(2024);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
}
