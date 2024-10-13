<?php
namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/public/mahasiswa_data.xlsx'); // Adjust this path if needed

        $data = Excel::toArray([], $filePath);

        foreach ($data[0] as $index => $row) {
            if ($index < 4) {
                // Lewati baris yang tidak relevan
                continue;
            }

            // Cek apakah 'nim' kosong
            if (empty($row[2])) {
                // Lewati jika 'nim' kosong
                continue;
            }

            Mahasiswa::create([
                'nama' => $row[1],
                'nim' => $row[2],
                'jenis_kelamin' => $row[3],
                'prodi' => $row[4],
                'jenjang' => $row[6],
                'agama' => $row[8],
                'angkatan' => 2024,
            ]);
        }


        echo "Seeding completed.\n";
    }
}
