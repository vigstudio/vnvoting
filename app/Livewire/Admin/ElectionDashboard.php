<?php

namespace App\Livewire\Admin;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Component;

class ElectionDashboard extends Component
{
    public Election $election;

    public function mount(Election $election): void
    {
        $this->election = $election->load('positions.candidates');
    }

    /**
     * Thống kê tổng quan cho cuộc bầu cử.
     */
    #[Computed]
    public function overview(): array
    {
        $totalBallots = Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->count();

        $totalExpected = Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->sum('expected_count');

        $totalEntered = Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->sum('entered_count');

        $totalInvalid = Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->sum('invalid_count');

        $totalValid = $totalEntered - $totalInvalid;

        $totalCounters = Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->distinct('user_id')
            ->count('user_id');

        return [
            'total_ballots' => $totalBallots,
            'total_expected' => $totalExpected,
            'total_entered' => $totalEntered,
            'total_valid' => $totalValid,
            'total_invalid' => $totalInvalid,
            'total_counters' => $totalCounters,
        ];
    }

    /**
     * Kết quả chi tiết theo từng chức vụ.
     */
    #[Computed]
    public function positionResults(): array
    {
        $results = [];

        foreach ($this->election->positions as $position) {
            $completedBallots = Ballot::where('election_id', $this->election->id)
                ->where('position_id', $position->id)
                ->whereNotNull('counted_at')
                ->get();

            $completedBallotIds = $completedBallots->pluck('id');
            $posExpected = $completedBallots->sum('expected_count');
            $posEntered = $completedBallots->sum('entered_count');
            $posInvalid = $completedBallots->sum('invalid_count');
            $posValid = $posEntered - $posInvalid;

            $candidateVotes = DB::table('votes')
                ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
                ->whereIn('votes.ballot_id', $completedBallotIds)
                ->select('candidates.id', 'candidates.name', 'candidates.sort_order', DB::raw('COUNT(*) as total_votes'))
                ->groupBy('candidates.id', 'candidates.name', 'candidates.sort_order')
                ->orderByDesc('total_votes')
                ->get();

            // Tính phần trăm phiếu trên tổng số phiếu hợp lệ
            $candidatesWithPercentage = $candidateVotes->map(function ($candidate) use ($posValid) {
                $percentage = $posValid > 0 ? round(($candidate->total_votes / $posValid) * 100, 2) : 0;
                $candidate->percentage = $percentage;
                return $candidate;
            });

            $results[] = [
                'position' => $position,
                'ballot_count' => $completedBallots->count(),
                'total_expected' => $posExpected,
                'total_valid' => $posValid,
                'total_invalid' => $posInvalid,
                'candidates' => $candidatesWithPercentage,
            ];
        }

        return $results;
    }

    /**
     * Thống kê theo từng kiểm phiếu viên.
     */
    #[Computed]
    public function counterStats()
    {
        return Ballot::where('election_id', $this->election->id)
            ->whereNotNull('counted_at')
            ->whereNotNull('user_id')
            ->with('user')
            ->select('user_id', DB::raw('COUNT(*) as total_ballots'), DB::raw('SUM(entered_count) as total_entered'), DB::raw('SUM(invalid_count) as total_invalid'))
            ->groupBy('user_id')
            ->get();
    }

    #[Layout('components.layouts.app')]
    #[Title('Dashboard Tổng Hợp')]
    public function render()
    {
        return view('livewire.admin.election-dashboard');
    }
}
