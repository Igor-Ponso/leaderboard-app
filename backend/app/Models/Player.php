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
        'email',
        'birth_date',
        'score',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];
}
