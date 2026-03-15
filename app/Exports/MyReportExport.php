<?php

namespace App\Exports;

use App\Models\Election;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MyReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        protected Election $election,
        protected int $userId
    ) {}

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->election->positions()->orderBy('sort_order')->get() as $position) {
            $sheets[] = new MyPositionReportSheet($this->election, $position, $this->userId);
        }

        return $sheets;
    }
}
