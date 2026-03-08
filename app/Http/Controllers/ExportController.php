<?php

namespace App\Http\Controllers;

use App\Exports\ElectionResultsExport;
use App\Models\Election;
use App\Services\VoteCounter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(
        protected VoteCounter $counter
    ) {}

    public function excel(Election $election): BinaryFileResponse
    {
        $fileName = 'ket-qua-bau-cu-' . str()->slug($election->title) . '-' . now()->format('Ymd-His') . '.xlsx';

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

        $fileName = 'ket-qua-bau-cu-' . str()->slug($election->title) . '-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($fileName);
    }
}
