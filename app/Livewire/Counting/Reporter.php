<?php

namespace App\Livewire\Counting;

use App\Models\Election;
use App\Models\Ballot;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Component;

class Reporter extends Component
{
    public Election $election;

    public function mount(Election $election): void
    {
        $this->election = $election->load('positions.candidates');
    }

    /**
     * Lấy tất cả lô phiếu đã hoàn thành của user hiện tại cho cuộc bầu cử này.
     */
    #[Computed]
    public function myBallots()
    {
        return Ballot::where('election_id', $this->election->id)
            ->where('user_id', auth()->id())
            ->whereNotNull('counted_at')
            ->with(['position', 'votes.candidate'])
            ->orderBy('counted_at', 'asc')
            ->get();
    }

    /**
     * Tổng hợp số phiếu của từng ứng viên do user hiện tại đếm.
     */
    #[Computed]
    public function mySummary(): array
    {
        $summary = [];

        foreach ($this->election->positions as $position) {
            $ballots = Ballot::where('election_id', $this->election->id)
                ->where('user_id', auth()->id())
                ->where('position_id', $position->id)
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
                ->select('candidates.id', 'candidates.name', 'candidates.sort_order', DB::raw('COUNT(*) as total_votes'))
                ->groupBy('candidates.id', 'candidates.name', 'candidates.sort_order')
                ->orderBy('candidates.sort_order')
                ->get();

            // Tính phần trăm phiếu trên tổng số phiếu hợp lệ
            $candidatesWithPercentage = $candidateVotes->map(function ($candidate) use ($totalValid) {
                $percentage = $totalValid > 0 ? round(($candidate->total_votes / $totalValid) * 100, 2) : 0;
                $candidate->percentage = $percentage;
                return $candidate;
            });

            $summary[] = [
                'position' => $position,
                'total_ballots_blocks' => $ballotIds->count(),
                'total_expected' => $totalExpected,
                'total_valid' => $totalValid,
                'total_invalid' => $totalInvalid,
                'candidates' => $candidatesWithPercentage,
            ];
        }

        return $summary;
    }

    /**
     * Delete a ballot block and all its votes.
     * Only allows deleting if the ballot belongs to the current user.
     */
    public function deleteBallot(int $ballotId): void
    {
        $ballot = Ballot::where('id', $ballotId)
            ->where('user_id', auth()->id())
            ->first();

        if ($ballot) {
            // Check threshold status before deleting to accurately show what was removed
            $positionTitle = $ballot->position->title ?? 'Không rõ chức vụ';

            // Delete all associated votes first
            DB::table('votes')->where('ballot_id', $ballot->id)->delete();

            // Delete the ballot
            $ballot->delete();

            // Force computed properties to refresh
            unset($this->myBallots);
            unset($this->mySummary);

            session()->flash('status', "✅ Đã xóa thành công Lô phiếu của chức vụ '{$positionTitle}'. Bạn có thể kiểm đếm lại từ đầu.");
        } else {
            session()->flash('error', '⚠️ Lô phiếu không tồn tại hoặc bạn không có quyền xóa Lô phiếu này.');
        }
    }

    #[Layout('components.layouts.app')]
    #[Title('Báo Cáo Kiểm Phiếu')]
    public function render()
    {
        return view('livewire.counting.reporter');
    }
}
