<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ballot_id',
        'candidate_id',
        'entry_number',
        'is_invalid',
    ];

    protected function casts(): array
    {
        return [
            'entry_number' => 'integer',
            'is_invalid' => 'boolean',
        ];
    }

    /**
     * Quan hệ với ballot
     */
    public function ballot()
    {
        return $this->belongsTo(Ballot::class);
    }

    /**
     * Quan hệ với candidate
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
