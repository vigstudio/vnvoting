<?php

namespace App\Http\Controllers;

use App\Exports\ElectionResultsExport;
use App\Exports\MyReportExport;
use App\Models\Ballot;
use App\Models\Election;
use App\Services\VoteCounter;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(
        protected VoteCounter $counter
    ) {}

    public function excel(Election $election): BinaryFileResponse
    {
        $fileName = 'ket-qua-bau-cu-'.str()->slug($election->title).'-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(
            new ElectionResultsExport($election, $this->counter),
            $fileName
        );
    }

    public function pdf(Election $election)
    {
        $election->load(['positions.candidates', 'positions.ballots']);

        $positionsData = [];
        foreach ($election->positions()->orderBy('sort_order')->get() as $position) {
            $results = $this->counter->getPositionResults($position);
            $totalVotes = $position->ballots()->sum('entered_count');

            $positionsData[] = [
                'position' => $position,
                'results' => $results,
                'total_votes' => $totalVotes,
            ];
        }

        $pdf = Pdf::loadView('pdf.election-results', [
            'election' => $election,
            'positionsData' => $positionsData,
        ]);

        $fileName = 'ket-qua-bau-cu-'.str()->slug($election->title).'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($fileName);
    }

    public function myExcel(Election $election): BinaryFileResponse
    {
        $fileName = 'bao-cao-ca-nhan-'.str()->slug($election->title).'-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(
            new MyReportExport($election, auth()->id()),
            $fileName
        );
    }

    public function myPdf(Election $election)
    {
        $election->load('positions.candidates');
        $userId = auth()->id();
        $userName = auth()->user()->name;

        $positionsData = [];
        foreach ($election->positions()->orderBy('sort_order')->get() as $position) {
            $ballots = Ballot::where('election_id', $election->id)
                ->where('user_id', $userId)
                ->where('position_id', $position->id)
                ->whereNotNull('counted_at')
                ->get();

            $ballotIds = $ballots->pluck('id');
            $totalExpected = $ballots->sum('expected_count');
            $totalEntered = $ballots->sum('entered_count');
            $totalInvalid = $ballots->sum('invalid_count');
            $totalValid = $totalEntered - $totalInvalid;

            $candidateVotes = \Illuminate\Support\Facades\DB::table('votes')
                ->join('candidates', 'votes.candidate_id', '=', 'candidates.id')
                ->whereIn('votes.ballot_id', $ballotIds)
                ->where('votes.is_invalid', false)
                ->select('candidates.name', 'candidates.sort_order', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_votes'))
                ->groupBy('candidates.name', 'candidates.sort_order')
                ->orderBy('candidates.sort_order')
                ->get()
                ->map(function ($candidate) use ($totalValid) {
                    $candidate->percentage = $totalValid > 0 ? round(($candidate->total_votes / $totalValid) * 100, 2) : 0;

                    return $candidate;
                });

            $positionsData[] = [
                'position' => $position,
                'total_ballots_blocks' => $ballotIds->count(),
                'total_expected' => $totalExpected,
                'total_valid' => $totalValid,
                'total_invalid' => $totalInvalid,
                'candidates' => $candidateVotes,
                'ballots' => $ballots,
            ];
        }

        $pdf = Pdf::loadView('pdf.my-report', [
            'election' => $election,
            'positionsData' => $positionsData,
            'userName' => $userName,
        ]);

        $fileName = 'bao-cao-ca-nhan-'.str()->slug($election->title).'-'.now()->format('Ymd-His').'.pdf';

        return $pdf->download($fileName);
    }
}
