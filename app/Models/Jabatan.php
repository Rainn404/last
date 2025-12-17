<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jabatan';
    
    protected $fillable = [
        'nama_jabatan',
        'deskripsi',
        'level',
        'status',
        'id_divisi'
    ];

    protected $casts = [
        'status' => 'boolean',
        'level' => 'integer'
    ];

    protected $table = 'jabatans';

    // Scope untuk jabatan aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }
}