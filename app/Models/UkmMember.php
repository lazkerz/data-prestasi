<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmMember extends Model
{
    protected $table = 'ukm_members';

    protected $fillable = ['ukm_id', 'mahasiswa_id', 'position'];

    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
