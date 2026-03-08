<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'position_id',
        'user_id',
        'expected_count',
        'entered_count',
        'counted_at',
    ];

    protected function casts(): array
    {
        return [
            'expected_count' => 'integer',
            'entered_count' => 'integer',
            'counted_at' => 'datetime',
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
     * Quan hệ với position
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Quan hệ với user (người kiểm phiếu)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với votes
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Kiểm tra ballot đã hoàn thành chưa
     */
    public function isComplete(): bool
    {
        return $this->counted_at !== null;
    }

    /**
     * Kiểm tra có nằm trong ngưỡng cho phép (50-150%)
     */
    public function isWithinThreshold(): bool
    {
        if ($this->expected_count === 0) {
            return false;
        }

        $percentage = ($this->entered_count / $this->expected_count) * 100;

        return $percentage >= 50 && $percentage <= 150;
    }

    /**
     * Tính phần trăm đã nhập
     */
    public function getPercentageAttribute(): float
    {
        if ($this->expected_count === 0) {
            return 0;
        }

        return round(($this->entered_count / $this->expected_count) * 100, 1);
    }
}
