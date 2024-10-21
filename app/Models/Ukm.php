<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukm extends Model
{
    use HasFactory;

    protected $table = 'ukm';

    protected $fillable = [
        'nama',
        'file_prestasi',
    ];

    // Relasi many-to-many dengan Mahasiswa melalui tabel pivot 'mahasiswa_ukm'
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_ukm')
                    ->withPivot('jabatan') // Menambahkan kolom jabatan di relasi pivot
                    ->withTimestamps();
    }
}

