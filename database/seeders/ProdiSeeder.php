<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodis = [
            ['nama_prodi' => 'Adm. Bisnis'],
            ['nama_prodi' => 'AKP'],
            ['nama_prodi' => 'Akuntansi'],
            ['nama_prodi' => 'Keu & Perbankan'],
            ['nama_prodi' => 'Perbankan Syariah'],
            ['nama_prodi' => 'Manajemen Bisnis'],
            ['nama_prodi' => 'Manajemen Informatika'],
            ['nama_prodi' => 'MRKG'],
            ['nama_prodi' => 'T. Elektronika'],
            ['nama_prodi' => 'Teknik Komputer'],
            ['nama_prodi' => 'T. Konversi Energi'],
            ['nama_prodi' => 'Teknik Mesin'],
            ['nama_prodi' => 'TPJJ'],
            ['nama_prodi' => 'Teknik Sipil'],
            ['nama_prodi' => 'T. Telekomunikasi'],
            ['nama_prodi' => 'TRET'],
            ['nama_prodi' => 'TRIL'],
            ['nama_prodi' => 'TRJT'],
            ['nama_prodi' => 'TRKI'],
            ['nama_prodi' => 'TRM'],
            ['nama_prodi' => 'TRMG'],
            ['nama_prodi' => 'TRO'],
            ['nama_prodi' => 'TRPF'],
            ['nama_prodi' => 'TRPL'],
            ['nama_prodi' => 'MICE'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}
