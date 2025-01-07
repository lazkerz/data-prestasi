<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'mahasiswa_id',
        'nama_prestasi',
        'deskripsi_prestasi',
        'jenis_prestasi',
        'tingkatan_prestasi',
        'tahun_prestasi',
        'file_prestasi',
    ];

    // Relasi ke model Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
