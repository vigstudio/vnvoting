<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Quan hệ với positions
     */
    public function positions()
    {
        return $this->hasMany(Position::class)->orderBy('sort_order');
    }

    /**
     * Quan hệ với ballots
     */
    public function ballots()
    {
        return $this->hasMany(Ballot::class);
    }

    /**
     * Scope để lấy election đang hoạt động
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
