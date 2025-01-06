<?php

// app/Models/Mahasiswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nama',
        'nim',
        'jenis_kelamin',
        'prodi',
        'jenjang',
        'agama',
        'angkatan',
    ];

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class);
    }

    public function ukms()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_members')
            ->withPivot('position')
            ->withTimestamps();
    }

    public function hmps()
    {
        return $this->belongsToMany(Hmps::class, 'hmps_members')
            ->withPivot('position')
            ->withTimestamps();
    }
}
