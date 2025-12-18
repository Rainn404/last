<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'criteria';
    protected $primaryKey = 'id_criterion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'code',
        'description',
        'priority',
        'weight',
        'status',
        'type'
    ];

    protected $casts = [
        'priority'  => 'integer',
        'status'    => 'boolean',
        'weight'    => 'float'
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Scope: hanya kriteria aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope: mengurutkan kriteria
    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')->orderBy('name');
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
                $criterion->id_criterion => "{$criterion->code} - {$criterion->name}"
            ]);
    }
}
