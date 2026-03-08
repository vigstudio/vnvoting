<?php

namespace App\Exports;

use App\Models\Election;
use App\Services\VoteCounter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ElectionResultsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        protected Election $election,
        protected VoteCounter $counter
    ) {}

    public function sheets(): array
    {
        $sheets = [];

        $positions = $this->election->positions()->orderBy('sort_order')->get();

        foreach ($positions as $position) {
            $sheets[] = new PositionResultsExport($position, $this->counter);
        }

        return $sheets;
    }
}
