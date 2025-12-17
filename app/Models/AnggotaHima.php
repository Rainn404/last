<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaHima extends Model
{
    use HasFactory;

    protected $table = 'anggota_hima';
    protected $primaryKey = 'id_anggota_hima';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama',
        'nim',
        'id_divisi',
        'id_jabatan',
        'semester',
        'status',
        'foto',
    ];

    protected $casts = [
        'status' => 'boolean',
        'semester' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ---------------------------------------------------
     |  RELASI
     --------------------------------------------------- */

    // Relasi ke tabel Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi')
                    ->withDefault([
                        'nama_divisi' => 'Tidak ada divisi',
                    ]);
    }

    // Relasi ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan')
                    ->withDefault([
                        'nama_jabatan' => 'Tidak ada jabatan',
                    ]);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id')
                    ->withDefault([
                        'name'  => 'Tidak ada user',
                        'email' => '-',
                    ]);
    }

    /* ---------------------------------------------------
     |  ACCESSORS
     --------------------------------------------------- */

    // Status (Aktif / Tidak Aktif)
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }

    // Foto URL otomatis
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) .
               '&background=3B82F6&color=fff';
    }

    /* ---------------------------------------------------
     |  SCOPES
     --------------------------------------------------- */

    // Hanya anggota aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Hanya anggota tidak aktif
    public function scopeTidakAktif($query)
    {
        return $query->where('status', false);
    }

    // Search nama / NIM
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        });
    }

    /* ---------------------------------------------------
     |  VALIDASI NIM UNIK
     --------------------------------------------------- */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (static::where('nim', $model->nim)->exists()) {
                throw new \Exception('NIM sudah terdaftar');
            }
        });

        static::updating(function ($model) {
            if (static::where('nim', $model->nim)
                ->where('id_anggota_hima', '!=', $model->id_anggota_hima)
                ->exists()
            ) {
                throw new \Exception('NIM sudah terdaftar');
            }
        });
    }
}
