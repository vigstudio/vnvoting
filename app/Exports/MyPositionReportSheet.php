<?php

namespace App\Exports;

use App\Models\Ballot;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MyPositionReportSheet implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        protected Election $election,
        protected Position $position,
        protected int $userId
    ) {}

    public function array(): array
    {
        $ballots = Ballot::where('election_id', $this->election->id)
            ->where('user_id', $this->userId)
            ->where('position_id', $this->position->id)
            ->whereNotNull('counted_at')
            ->get();

        $ballotIds = $ballots->pluck('id');
        $totalExpected = $ballots->sum('expected_count');
        $totalEntered = $ballots->sum('entered_count');
        $totalInvalid = $ballots->sum('invalid_count');
        $totalValid = $totalEntered - $totalInvalid;

        $candidateVotes = DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
            ->whereIn('votes.ballot_id', $ballotIds)
            ->where('votes.is_invalid', false)
            ->select('candidates.name', 'candidates.sort_order', DB::raw('COUNT(*) as total_votes'))
            ->groupBy('candidates.name', 'candidates.sort_order')
            ->orderBy('candidates.sort_order')
            ->get();

        $data = [
            ['BÁO CÁO CÁ NHÂN - KIỂM ĐẾM PHIẾU'],
            ['Cuộc bầu cử:', $this->election->title],
            ['Chức vụ:', $this->position->title],
            ['Số lô phiếu:', $ballotIds->count()],
            ['Tổng phiếu phát ra:', $totalExpected],
            ['Phiếu hợp lệ:', $totalValid],
            ['Phiếu không hợp lệ:', $totalInvalid],
            [],
            ['STT', 'Tên ứng viên', 'Số phiếu', 'Tỷ lệ (%)'],
        ];

        foreach ($candidateVotes as $candidate) {
            $percentage = $totalValid > 0 ? round(($candidate->total_votes / $totalValid) * 100, 2) : 0;
            $data[] = [
                $candidate->sort_order + 1,
                $candidate->name,
                $candidate->total_votes,
                $percentage.'%',
            ];
        }

        $data[] = [];
        $data[] = ['TỔNG PHIẾU HỢP LỆ:', $totalValid];
        $data[] = ['TỔNG PHIẾU KHÔNG HỢP LỆ:', $totalInvalid];

        // Chi tiết từng lô
        $data[] = [];
        $data[] = [];
        $data[] = ['CHI TIẾT TỪNG LÔ PHIẾU'];
        $data[] = ['Lô #', 'Phiếu phát ra', 'Đã nhập', 'Không hợp lệ', 'Thời gian hoàn thành'];

        foreach ($ballots as $index => $ballot) {
            $data[] = [
                $index + 1,
                $ballot->expected_count,
                $ballot->entered_count,
                $ballot->invalid_count,
                $ballot->counted_at?->format('d/m/Y H:i:s'),
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            9 => [
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
        return mb_substr($this->position->title, 0, 25);
    }
}
