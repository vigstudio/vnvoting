<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'title',
        'ballot_color',
        'max_votes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'max_votes' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Quan hệ với election
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Quan hệ với candidates
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class)->orderBy('sort_order');
    }

    /**
     * Quan hệ với ballots
     */
    public function ballots()
    {
        return $this->hasMany(Ballot::class);
    }
}
