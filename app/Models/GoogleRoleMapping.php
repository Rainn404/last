<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleRoleMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_pattern',
        'role',
        'priority',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer'
    ];

    /**
     * Scope untuk mapping aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk urutan priority
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Cek apakah email cocok dengan pattern
     */
    public function matchesEmail($email)
    {
        // Jika pattern dimulai dan diakhiri dengan *, gunakan wildcard
        if (str_starts_with($this->email_pattern, '*') && str_ends_with($this->email_pattern, '*')) {
            $pattern = str_replace('*', '', $this->email_pattern);
            return str_contains($email, $pattern);
        }

        // Jika pattern dimulai dengan *, cocokkan akhiran
        if (str_starts_with($this->email_pattern, '*')) {
            $pattern = ltrim($this->email_pattern, '*');
            return str_ends_with($email, $pattern);
        }

        // Jika pattern diakhiri dengan *, cocokkan awalan
        if (str_ends_with($this->email_pattern, '*')) {
            $pattern = rtrim($this->email_pattern, '*');
            return str_starts_with($email, $pattern);
        }

        // Exact match
        return $email === $this->email_pattern;
    }

    /**
     * Cari role untuk email tertentu
     */
    public static function findRoleForEmail($email)
    {
        $mapping = self::active()
            ->ordered()
            ->get()
            ->first(function ($mapping) use ($email) {
                return $mapping->matchesEmail($email);
            });

        return $mapping ? $mapping->role : 'mahasiswa'; // Default role
    }
}
