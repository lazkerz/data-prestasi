<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HmpsMember extends Model
{
    protected $table = 'hmps_members';

    protected $fillable = ['hmps_id', 'mahasiswa_id', 'position'];

    public function hmps()
    {
        return $this->belongsTo(Hmps::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
