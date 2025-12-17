<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criterion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'order',
        'type'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer'
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Scope: hanya kriteria aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: mengurutkan kriteria
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function rowComparisons()
    {
        return $this->hasMany(PairwiseComparison::class, 'row_criterion_id');
    }

    public function columnComparisons()
    {
        return $this->hasMany(PairwiseComparison::class, 'column_criterion_id');
    }

    public function alternativeScores()
    {
        return $this->hasMany(AlternativeScore::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    // Accessor: ubah "benefit" -> "Benefit"
    public function getTypeNameAttribute()
    {
        return $this->type === 'benefit' ? 'Benefit' : 'Cost';
    }

    // Mutator: code otomatis uppercase
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // Untuk dropdown form (select)
    public static function getForDropdown()
    {
        return self::active()
            ->ordered()
            ->get()
            ->mapWithKeys(fn($criterion) => [
                $criterion->id => "{$criterion->code} - {$criterion->name}"
            ]);
    }
}
