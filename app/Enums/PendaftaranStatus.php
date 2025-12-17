<?php

namespace App\Enums;

enum PendaftaranStatus: string
{
    case PENDING = 'pending';
    case INTERVIEW = 'interview';
    case ACCEPTED = 'diterima';
    case REJECTED = 'ditolak';

    /**
     * Get all valid values for validation
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get all valid values as comma-separated string for in() validation
     */
    public static function validationString(): string
    {
        return implode(',', self::values());
    }
}
