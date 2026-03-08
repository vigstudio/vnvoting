<?php

namespace App\Exports;

use App\Models\Position;
use App\Services\VoteCounter;
use Illuminate\Contracts\Support\Arrayable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PositionResultsExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected Position $position,
        protected VoteCounter $counter
    ) {}

    public function array(): array
    {
        $results = $this->counter->getPositionResults($this->position);

        $data = [
            [
                'Cấp chức vụ:',
                $this->position->title,
            ],
            [
                'Màu phiếu:',
                $this->position->ballot_color,
            ],
            [
                'Tổng số ứng viên:',
                $this->position->candidates()->count(),
            ],
            [],
            [],
            [
                'STT',
                'Tên ứng viên',
                'Số phiếu',
                'Tỷ lệ (%)',
            ],
        ];

        foreach ($results as $result) {
            $data[] = [
                $result['candidate_number'],
                $result['name'],
                $result['vote_count'],
                $result['percentage'] . '%',
            ];
        }

        $totalVotes = $this->position->ballots()->sum('entered_count');
        $data[] = [];
        $data[] = [
            'TỔNG SỐ PHIẾU:',
            $totalVotes,
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],
            6 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        $cleanTitle = mb_substr($this->position->title, 0, 25);
        return $cleanTitle;
    }
}
