<?php

namespace App\Models;

use App\Traits\HasHash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes, HasHash;

    protected $fillable = [
        'name',
        'hash',
        'birth_date',
        'score',
        'address',
        'qr_code_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'address' => 'array',
    ];

    protected $appends = ['qr_code_url'];


    /**
     * Accessors and Mutators
     * 
     * @var string | null
     */
    public function getBirthDateAttribute($value): string|null
    {
        if (!$value) {
            return null;
        }

        // verify if the value is already a Carbon instance
        if ($value instanceof \Illuminate\Support\Carbon) {
            return $value->format('d/m/Y');
        }

        // if not, parse the date and format it
        return \Illuminate\Support\Carbon::parse($value)->format('d/m/Y');
    }

    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }


}
