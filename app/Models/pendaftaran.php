<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'id_user',
        'nim', 
        'nama',
        'semester',
        'alasan_mendaftar',
        'id_divisi',
        'id_jabatan',
        'alasan_divisi',
        'pengalaman',
        'skill',
        'dokumen',
        'no_hp',
        'status_pendaftaran',
        'submitted_at',
        'divalidasi_oleh',
        'validated_at',
        'interview_date',
        'notes',
        'wa_sent'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'interview_date' => 'datetime',
    ];

    /**
     * Get the user that owns the pendaftaran.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Get the divisi.
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    /**
     * Get the jabatan.
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    /**
     * Get the validator user.
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh', 'id');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'submitted' => 'bg-secondary text-white', // gray
            'verifying' => 'bg-primary text-white', // blue
            'interview' => 'bg-warning text-dark', // yellow
            'accepted' => 'bg-success text-white', // green
            'rejected' => 'bg-danger text-white', // red
        ];

        return $statuses[$this->status_pendaftaran] ?? 'bg-secondary text-white';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'submitted' => 'Submitted',
            'verifying' => 'Verifying',
            'interview' => 'Interview',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
        ];

        return $labels[$this->status_pendaftaran] ?? 'Tidak Diketahui';
    }
}