<?php

namespace App\Livewire\Counting;

use App\Models\Ballot;
use App\Models\Election;
use App\Services\VoteCounter;
use App\Services\VoteValidationException;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Component;

class BallotEntry extends Component
{
    public Election $election;

    public array $selectedCandidates = [];
    public int $expectedCount = 0;
    public ?Ballot $currentBallot = null;
    public array $recentEntries = [];
    public ?int $selectedPositionId = null;

    public bool $showResults = false;

    protected VoteCounter $counter;

    public function boot(VoteCounter $counter): void
    {
        $this->counter = $counter;
    }

    public function mount(Election $election): void
    {
        $this->election = $election->load('positions.candidates');
        $this->recentEntries = session('recent_entries_' . $this->election->id, []);
    }

    protected function rules(): array
    {
        return [
            'selectedCandidates' => ['required', 'array', 'min:1'],
            'expectedCount' => ['required', 'integer', 'min:1'],
            'selectedPositionId' => ['required', 'exists:positions,id'],
        ];
    }

    #[Computed]
    public function currentBallotKey(): string
    {
        return session('current_ballot_' . $this->election->id, '');
    }

    public function startBallot(): void
    {
        $this->validateOnly('expectedCount');

        $position = $this->election->positions()->findOrFail($this->selectedPositionId);

        $this->currentBallot = $position->ballots()
            ->where('user_id', auth()->id())
            ->whereNull('counted_at')
            ->firstOr(function () use ($position) {
                return $position->ballots()->create([
                    'election_id' => $this->election->id,
                    'user_id' => auth()->id(),
                    'expected_count' => $this->expectedCount,
                    'entered_count' => 0,
                ]);
            });

        session(['current_ballot_' . $this->election->id => $this->currentBallot->id]);
        $this->recentEntries = [];
        session(['recent_entries_' . $this->election->id => $this->recentEntries]);
        $this->selectedCandidates = [];
    }

    public function submitBallot(): void
    {
        if (!$this->currentBallot) {
            $this->addError('selectedCandidates', 'Vui lòng bắt đầu kiểm phiếu trước.');
            return;
        }

        $this->validateOnly('selectedCandidates');

        try {
            $inputString = implode(', ', $this->selectedCandidates);
            $createdVoteIds = $this->counter->recordBallot($inputString, $this->currentBallot);

            // Track in history
            array_unshift($this->recentEntries, [
                'input' => $inputString,
                'time' => now()->format('H:i:s'),
                'count' => $this->currentBallot->entered_count, // was already incremented in recordBallot
                'vote_ids' => $createdVoteIds
            ]);

            // Limit history to 10
            $this->recentEntries = array_slice($this->recentEntries, 0, 10);

            // Save to session
            session(['recent_entries_' . $this->election->id => $this->recentEntries]);

            $this->selectedCandidates = [];
            $this->dispatch('ballot-recorded');
        } catch (VoteValidationException $e) {
            $this->addError('selectedCandidates', $e->getMessage());
        }
    }

    public function recordInvalidBallot(): void
    {
        if (!$this->currentBallot) {
            return;
        }

        // Validate that we haven't exceeded the expected count
        if ($this->currentBallot->entered_count >= $this->currentBallot->expected_count) {
            $this->addError('selectedCandidates', 'Đã lưu đủ số phiếu (' . $this->currentBallot->expected_count . '). Không thể thêm phiếu mới.');
            return;
        }

        // Tăng số đếm
        $this->currentBallot->increment('entered_count');
        $this->currentBallot->increment('invalid_count');

        // Thêm vào history nhưng không có vote_ids vì không có ứng viên nào nhận được phiếu
        $newCount = $this->currentBallot->fresh()->entered_count;
        array_unshift($this->recentEntries, [
            'count' => $newCount,
            'input' => 'Phiếu không hợp lệ',
            'vote_ids' => [],
        ]);

        // Save to session
        session(['recent_entries_' . $this->election->id => $this->recentEntries]);

        $this->selectedCandidates = [];
        $this->dispatch('ballot-recorded');
    }

    public function undoEntry(int $index): void
    {
        if (!isset($this->recentEntries[$index])) {
            return;
        }

        $entry = $this->recentEntries[$index];
        $isInvalidEntry = ($entry['input'] === 'Phiếu không hợp lệ');
        $voteIds = $entry['vote_ids'] ?? [];

        // Polyfill cho các entry cũ (trước khi có tính năng lưu vote_ids)
        if (!$isInvalidEntry && empty($voteIds) && !empty($entry['input']) && $this->currentBallot) {
            $inputCount = count(explode(',', $entry['input']));
            $voteIds = \App\Models\Vote::where('ballot_id', $this->currentBallot->id)
                ->orderBy('id', 'desc')
                ->limit($inputCount)
                ->pluck('id')
                ->toArray();
        }

        if ($this->currentBallot) {
            // Xóa các vote đã tạo từ database (nếu có)
            if (!empty($voteIds)) {
                \App\Models\Vote::whereIn('id', $voteIds)->delete();
            }

            // Giảm số đếm xuống đúng 1 lần (vì mỗi entry tương ứng 1 lần nhấn Enter = 1 phiếu đếm)
            if ($this->currentBallot->entered_count > 0) {
                $this->currentBallot->decrement('entered_count');
            }

            // Giảm phiểu không hợp lệ nếu entry là phiếu hỏng
            if ($isInvalidEntry && $this->currentBallot->invalid_count > 0) {
                $this->currentBallot->decrement('invalid_count');
            }

            // Xóa entry khỏi history
            array_splice($this->recentEntries, $index, 1);

            // Cập nhật lại số thứ tự (count) cho các entry cũ hơn
            // Chú ý: Các entry mảng [0] là mới nhất, mảng cuối là cũ nhất
            // Nếu ta xóa 1 entry ở giữa, tổng entered_count lúc đó đã thay đổi
            // Không nhất thiết phải sửa lại 'count' hiển thị, nhưng nếu cầu kỳ có thể map() lại.
            // Để đơn giản, update lại count hiển thị từ count hiện tại giảm dần
            $currentCount = $this->currentBallot->fresh()->entered_count;
            foreach ($this->recentEntries as $i => &$hist) {
                $hist['count'] = $currentCount - $i;
            }

            // Lưu session
            session(['recent_entries_' . $this->election->id => $this->recentEntries]);
        }
    }

    public function toggleCandidate(int $candidateNumber): void
    {
        // Silently ignore if no active ballot or candidate number doesn't exist
        if (!$this->currentBallot) return;

        $candidateCount = $this->currentBallot->position->candidates->count();
        if ($candidateNumber < 1 || $candidateNumber > $candidateCount) return;

        $index = array_search($candidateNumber, $this->selectedCandidates);

        if ($index !== false) {
            // Remove if already selected
            unset($this->selectedCandidates[$index]);
            $this->selectedCandidates = array_values($this->selectedCandidates);
        } else {
            // Add if not selected
            $this->selectedCandidates[] = $candidateNumber;
            sort($this->selectedCandidates); // Keep them sorted for consistency
        }
    }

    public function finalizeBallot(): void
    {
        if (!$this->currentBallot || $this->currentBallot->counted_at) {
            return;
        }

        try {
            $this->counter->finalizeBallot($this->currentBallot);
            session()->forget('current_ballot_' . $this->election->id);
            session()->forget('recent_entries_' . $this->election->id);
            $this->currentBallot = null;
            $this->expectedCount = 0;
            $this->recentEntries = [];
            session()->flash('status', '✅ Đã hoàn thành block phiếu này!');
        } catch (VoteValidationException $e) {
            session()->flash('error', '⚠️ ' . $e->getMessage());
        }
    }

    public function cancelBallot(): void
    {
        session()->forget('current_ballot_' . $this->election->id);
        session()->forget('recent_entries_' . $this->election->id);
        $this->currentBallot = null;
        $this->selectedCandidates = [];
        $this->expectedCount = 0;
        $this->recentEntries = [];
    }

    public function toggleResults(): void
    {
        $this->showResults = !$this->showResults;
    }

    #[Computed]
    public function currentResults(): array
    {
        if (!$this->currentBallot) {
            return [];
        }

        return $this->counter->getResults($this->currentBallot)->toArray();
    }

    #[Computed]
    public function thresholdStatus(): array
    {
        if (!$this->currentBallot) {
            return [];
        }

        return $this->counter->checkThreshold($this->currentBallot);
    }

    public function render()
    {
        if (!$this->currentBallot && session('current_ballot_' . $this->election->id)) {
            $this->currentBallot = Ballot::find(session('current_ballot_' . $this->election->id));
        }

        return view('livewire.counting.ballot-entry', [
            'positions' => $this->election->positions()->with('candidates')->orderBy('sort_order')->get(),
        ]);
    }
}
