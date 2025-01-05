<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// app/Models/Ukm.php
class Ukm extends Model
{
    use HasFactory;

    protected $table = 'ukm';

    protected $fillable = [
        'nama',
        'user_id', // This will store the UKM account user ID
        'description',
    ];

    // Relation to User (UKM account)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to members (students)
    public function members()
    {
        return $this->belongsToMany(Mahasiswa::class, 'ukm_members', 'ukm_id', 'mahasiswa_id')
                    ->withPivot('position')
                    ->withTimestamps();
    }

}
