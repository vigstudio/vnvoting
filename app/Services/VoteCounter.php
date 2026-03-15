<?php

namespace App\Services;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VoteCounter
{
    protected array $results = [];

    /**
     * Parse input string thành array các số ứng viên
     * Ví dụ: "1, 2, 3" → [1, 2, 3]
     */
    public function parseBallotInput(string $input): array
    {
        // Split bằng khoảng trắng hoặc dấu phẩy
        $parts = preg_split('/[\s,]+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);

        $numbers = [];
        foreach ($parts as $part) {
            // Kiểm tra phải là số không
            if (! ctype_digit($part)) {
                throw VoteValidationException::invalidFormat($input);
            }

            $numbers[] = (int) $part;
        }

        // Loại bỏ trùng lặp và reset keys
        return array_values(array_unique($numbers));
    }

    /**
     * Validate các lựa chọn theo position
     */
    public function validateBallot(array $candidateNumbers, Position $position): void
    {
        $totalCandidates = $position->candidates->count();

        // Kiểm tra có chọn ứng viên nào không
        if (empty($candidateNumbers)) {
            throw VoteValidationException::noCandidates();
        }

        // Kiểm tra số lượng chọn có vượt quá max_votes không
        if ($position->max_votes > 0 && count($candidateNumbers) > $position->max_votes) {
            throw VoteValidationException::tooManyCandidates($position->max_votes, count($candidateNumbers));
        }

        // Kiểm tra các số có hợp lệ không (trong phạm vi 1 đến tổng số ứng viên)
        $invalidNumbers = [];
        foreach ($candidateNumbers as $number) {
            if ($number < 1 || $number > $totalCandidates) {
                $invalidNumbers[] = $number;
            }
        }

        if (! empty($invalidNumbers)) {
            throw VoteValidationException::invalidCandidates($invalidNumbers);
        }

        // Kiểm tra trùng lặp (đã loại bỏ ở parseBallotInput nhưng kiểm tra lại để chắc chắn)
        if (count($candidateNumbers) !== count(array_unique($candidateNumbers))) {
            throw VoteValidationException::duplicateSelection();
        }
    }

    /**
     * Ghi nhận một phiếu bầu vào hệ thống và trả về danh sách ID các Vote đã tạo
     *
     * @return array<int> Danh sách Vote IDs
     */
    public function recordBallot(string $input, Ballot $ballot): array
    {
        // Kiểm tra đã đạt số lượng phiếu kỳ vọng chưa
        if ($ballot->entered_count >= $ballot->expected_count) {
            throw VoteValidationException::ballotFull($ballot->expected_count);
        }

        // Lấy position của ballot
        $position = $ballot->position()->with('candidates')->firstOrFail();

        // Parse input
        $candidateNumbers = $this->parseBallotInput($input);

        // Validate
        $this->validateBallot($candidateNumbers, $position);

        // Tìm các candidates theo số thứ tự (1-based index)
        $candidates = $position->candidates
            ->sortBy('sort_order')
            ->values();

        $selectedCandidateIds = [];
        foreach ($candidateNumbers as $number) {
            // Chuyển về 0-based index
            $index = $number - 1;

            if ($candidates->has($index)) {
                $selectedCandidateIds[] = $candidates->get($index)->id;
            }
        }

        // Ghi votes vào database với transaction
        return DB::transaction(function () use ($ballot, $selectedCandidateIds) {
            $createdVoteIds = [];
            // entry_number = số thứ tự phiếu trong lô (1-based)
            $entryNumber = $ballot->entered_count + 1;

            foreach ($selectedCandidateIds as $candidateId) {
                // Tạo vote mới cho mỗi lần nhập
                $vote = \App\Models\Vote::create([
                    'ballot_id' => $ballot->id,
                    'candidate_id' => $candidateId,
                    'entry_number' => $entryNumber,
                ]);
                $createdVoteIds[] = $vote->id;
            }

            // Tăng entered_count của ballot
            $ballot->increment('entered_count');

            return $createdVoteIds;
        });
    }

    /**
     * Hoàn thành ballot và kiểm tra threshold
     */
    public function finalizeBallot(Ballot $ballot): void
    {
        // Kiểm tra threshold
        $status = $this->checkThreshold($ballot);

        if (! $status['within_threshold']) {
            throw VoteValidationException::thresholdOutOfRange($status['percentage']);
        }

        // Đánh dấu đã hoàn thành
        $ballot->update([
            'counted_at' => now(),
        ]);
    }

    /**
     * Kiểm tra ballot có nằm trong ngưỡng cho phép (50-150%) không
     */
    public function checkThreshold(Ballot $ballot): array
    {
        if ($ballot->expected_count === 0) {
            return [
                'within_threshold' => false,
                'percentage' => 0,
                'message' => 'Số phiếu kỳ vọng phải lớn hơn 0',
            ];
        }

        $percentage = round(($ballot->entered_count / $ballot->expected_count) * 100, 1);
        $withinThreshold = $percentage >= 50 && $percentage <= 150;

        $message = $withinThreshold
            ? 'Đúng khoảng cho phép'
            : "Số phiếu nhập ({$percentage}%) nằm ngoài khoảng cho phép (50-150%)";

        return [
            'within_threshold' => $withinThreshold,
            'percentage' => $percentage,
            'message' => $message,
        ];
    }

    /**
     * Lấy kết quả vote cho một ballot
     */
    public function getResults(Ballot $ballot): Collection
    {
        return Candidate::whereHas('position', function ($query) use ($ballot) {
            $query->where('id', $ballot->position_id);
        })
            ->withCount(['votes as vote_count' => function ($query) use ($ballot) {
                $query->where('ballot_id', $ballot->id);
            }])
            ->get()
            ->map(function ($candidate) {
                return [
                    'candidate_number' => $candidate->sort_order + 1,
                    'name' => $candidate->name,
                    'vote_count' => $candidate->vote_count ?? 0,
                ];
            })
            ->sortByDesc('vote_count')
            ->values();
    }

    /**
     * Lấy tổng số phiếu cho mỗi candidate của một position
     */
    public function getPositionResults(Position $position): Collection
    {
        return $position->candidates()
            ->withCount('votes')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($candidate) use ($position) {
                $totalVotes = $position->ballots()->sum('entered_count');

                return [
                    'candidate_number' => $candidate->sort_order + 1,
                    'name' => $candidate->name,
                    'vote_count' => $candidate->votes_count ?? 0,
                    'percentage' => $totalVotes > 0
                        ? round((($candidate->votes_count ?? 0) / $totalVotes) * 100, 1)
                        : 0,
                ];
            });
    }
}
