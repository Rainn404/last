<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'berita_id',
        'nama',
        'isi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi: komentar milik satu berita
     * FK   : berita_id (di komentar)
     * PK   : Id_berita (di berita)
     */
    public function berita()
{
    return $this->belongsTo(
        \App\Models\Berita::class,
        'berita_id',
        'id_berita' // ✅ BENAR
    );
}


    /**
     * Accessor optional: jika nama kosong, tampilkan "Anonim"
     */
    public function getNamaAttribute($value)
    {
        return $value ?: 'Anonim';
    }

    /**
     * Scope: komentar terbaru dulu
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope: filter komentar berdasarkan berita
     */
    public function scopeByBerita($query, $berita_id)
    {
        return $query->where('berita_id', $berita_id);
    }

    /**
     * Scope: pencarian komentar di dashboard admin
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('isi', 'LIKE', '%' . $keyword . '%')
                     ->orWhere('nama', 'LIKE', '%' . $keyword . '%');
    }
}
