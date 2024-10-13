<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestasi;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prestasi::create([
            'mahasiswa_id' => 1, // Pastikan mahasiswa dengan ID 1 sudah ada
            'nama_prestasi' => 'Juara 1 Lomba Karya Ilmiah',
            'deskripsi_prestasi' => 'Lomba tingkat nasional',
            'jenis_prestasi' => 'Akademik',
            'tingkatan_prestasi' => 'Nasional',
            'file_prestasi' => 'lomba_karya_ilmiah.pdf',
        ]);

        // Tambahkan data dummy lainnya sesuai kebutuhan
    }
}
