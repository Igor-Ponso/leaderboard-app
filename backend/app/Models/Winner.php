<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winner extends Model
{
    public $timestamps = false;

    protected $fillable = ['player_id', 'score', 'created_at'];

    /**
     * Relation with Player
     * 
     * @return BelongsTo<Player, Winner>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
