<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'name',
        'photo',
        'description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * Quan hệ với position
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Quan hệ với votes
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
