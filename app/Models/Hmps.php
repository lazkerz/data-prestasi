<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hmps extends Model
{
    use HasFactory;

    protected $table = 'hmps';

    protected $fillable = [
        'nama',
        'user_id',
        'prodi_id',
        'description',
    ];

    // Relation to User (HMPS account)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    // Relation to members (students)
    public function members()
    {
        return $this->belongsToMany(Mahasiswa::class, 'hmps_members')
                    ->withPivot('position')
                    ->withTimestamps();
    }
}
