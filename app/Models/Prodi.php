<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis'; // Add this line to explicitly set the table name
    protected $fillable = ['nama_prodi'];

    public function hmps()
    {
        return $this->hasOne(Hmps::class, 'prodi_id');
    }
}