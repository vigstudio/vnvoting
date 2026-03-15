<?php

namespace App\Livewire\Counting;

use App\Models\Ballot;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Reporter extends Component
{
    public Election $election;

    /** @var int|null ID của ballot đang xem chi tiết entries */
    public ?int $viewingBallotId = null;

    /** @var int|null Entry number đang chỉnh sửa */
    public ?int $editingEntryNumber = null;

    /** @var int|null Ballot ID của entry đang chỉnh sửa */
    public ?int $editingBallotId = null;

    /** @var array Danh sách candidate IDs đã chọn khi edit */
    public array $editSelectedCandidates = [];

    /** @var bool Đánh dấu phiếu đang edit là không hợp lệ */
    public bool $editIsInvalid = false;

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
            ->with(['position.candidates', 'votes.candidate'])
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
                ->where('votes.is_invalid', false)
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
     * Backfill entry_number cho votes cũ (chưa có entry_number).
     * Nhóm theo created_at và gán entry_number tuần tự.
     */
    protected function backfillEntryNumbers(int $ballotId): void
    {
        $ungrouped = Vote::where('ballot_id', $ballotId)
            ->whereNull('entry_number')
            ->orderBy('id')
            ->get();

        if ($ungrouped->isEmpty()) {
            return;
        }

        // Tìm entry_number lớn nhất hiện tại
        $maxEntry = Vote::where('ballot_id', $ballotId)
            ->whereNotNull('entry_number')
            ->max('entry_number') ?? 0;

        $nextEntry = $maxEntry + 1;

        // Nhóm theo created_at
        $grouped = $ungrouped->groupBy(fn ($v) => $v->created_at?->format('Y-m-d H:i:s'));

        foreach ($grouped as $group) {
            Vote::whereIn('id', $group->pluck('id'))
                ->update(['entry_number' => $nextEntry]);
            $nextEntry++;
        }
    }

    /**
     * Lấy danh sách entries (từng phiếu) trong một ballot, nhóm theo entry_number.
     */
    #[Computed]
    public function ballotEntries(): array
    {
        if (! $this->viewingBallotId) {
            return [];
        }

        $ballot = Ballot::where('id', $this->viewingBallotId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $ballot) {
            return [];
        }

        $votes = Vote::where('ballot_id', $ballot->id)
            ->with('candidate')
            ->orderBy('entry_number')
            ->orderBy('id')
            ->get();

        // Nhóm theo entry_number (tất cả đã được backfill)
        $entries = [];
        $groupedByEntry = $votes->groupBy('entry_number');

        foreach ($groupedByEntry as $entryNumber => $entryVotes) {
            $isInvalid = $entryVotes->contains('is_invalid', true);
            $entries[$entryNumber] = [
                'entry_number' => (int) $entryNumber,
                'is_invalid' => $isInvalid,
                'candidates' => $isInvalid ? [] : $entryVotes->map(fn ($v) => [
                    'id' => $v->candidate->id,
                    'name' => $v->candidate->name,
                    'sort_order' => $v->candidate->sort_order,
                ])->values()->toArray(),
                'vote_ids' => $entryVotes->pluck('id')->toArray(),
                'created_at' => $entryVotes->first()->created_at?->format('H:i:s'),
            ];
        }

        ksort($entries);

        return array_values($entries);
    }

    /**
     * Mở xem lịch sử entries của một ballot.
     */
    public function viewEntries(int $ballotId): void
    {
        if ($this->viewingBallotId === $ballotId) {
            // Toggle off
            $this->viewingBallotId = null;
        } else {
            // Backfill entry_number cho dữ liệu cũ trước khi hiển thị
            $this->backfillEntryNumbers($ballotId);
            $this->viewingBallotId = $ballotId;
        }

        // Reset editing state
        $this->cancelEdit();
        // Clear cached computed
        unset($this->ballotEntries);
    }

    /**
     * Bắt đầu chỉnh sửa một entry.
     */
    public function startEdit(int $ballotId, int $entryNumber): void
    {
        $ballot = Ballot::where('id', $ballotId)
            ->where('user_id', auth()->id())
            ->with('position.candidates')
            ->first();

        if (! $ballot) {
            return;
        }

        $this->editingBallotId = $ballotId;
        $this->editingEntryNumber = $entryNumber;

        // Lấy votes hiện tại của entry này
        $votes = Vote::where('ballot_id', $ballotId)
            ->where('entry_number', $entryNumber)
            ->get();

        $this->editIsInvalid = $votes->contains('is_invalid', true);

        if (! $this->editIsInvalid) {
            // Lấy sort_order (1-based) của các candidate đã chọn
            $candidates = $ballot->position->candidates->sortBy('sort_order')->values();
            $this->editSelectedCandidates = $votes->map(function ($vote) use ($candidates) {
                $index = $candidates->search(fn ($c) => $c->id === $vote->candidate_id);

                return $index !== false ? $index + 1 : null;
            })->filter()->values()->toArray();
        } else {
            $this->editSelectedCandidates = [];
        }
    }

    /**
     * Toggle candidate trong edit mode.
     */
    public function toggleEditCandidate(int $candidateNumber): void
    {
        $index = array_search($candidateNumber, $this->editSelectedCandidates);
        if ($index !== false) {
            unset($this->editSelectedCandidates[$index]);
            $this->editSelectedCandidates = array_values($this->editSelectedCandidates);
        } else {
            $this->editSelectedCandidates[] = $candidateNumber;
            sort($this->editSelectedCandidates);
        }
    }

    /**
     * Toggle trạng thái phiếu không hợp lệ khi edit.
     */
    public function toggleEditInvalid(): void
    {
        $this->editIsInvalid = ! $this->editIsInvalid;
        if ($this->editIsInvalid) {
            $this->editSelectedCandidates = [];
        }
    }

    /**
     * Lưu chỉnh sửa entry.
     */
    public function saveEdit(): void
    {
        if (! $this->editingBallotId || ! $this->editingEntryNumber) {
            return;
        }

        $ballot = Ballot::where('id', $this->editingBallotId)
            ->where('user_id', auth()->id())
            ->with('position.candidates')
            ->first();

        if (! $ballot) {
            $this->addError('editSelectedCandidates', 'Không tìm thấy lô phiếu.');

            return;
        }

        // Kiểm tra max_votes
        $position = $ballot->position;
        if (! $this->editIsInvalid && $position->max_votes > 0 && count($this->editSelectedCandidates) > $position->max_votes) {
            $this->addError('editSelectedCandidates', "Chỉ được chọn tối đa {$position->max_votes} ứng viên.");

            return;
        }

        if (! $this->editIsInvalid && empty($this->editSelectedCandidates)) {
            $this->addError('editSelectedCandidates', 'Phải chọn ít nhất một ứng viên hoặc đánh dấu phiếu không hợp lệ.');

            return;
        }

        $candidates = $position->candidates->sortBy('sort_order')->values();

        DB::transaction(function () use ($ballot, $candidates) {
            // Lấy votes cũ của entry này
            $oldVotes = Vote::where('ballot_id', $ballot->id)
                ->where('entry_number', $this->editingEntryNumber)
                ->get();

            $wasInvalid = $oldVotes->contains('is_invalid', true);

            // Xóa votes cũ
            Vote::where('ballot_id', $ballot->id)
                ->where('entry_number', $this->editingEntryNumber)
                ->delete();

            if ($this->editIsInvalid) {
                // Tạo vote đánh dấu không hợp lệ
                Vote::create([
                    'ballot_id' => $ballot->id,
                    'candidate_id' => $candidates->first()->id,
                    'entry_number' => $this->editingEntryNumber,
                    'is_invalid' => true,
                ]);

                // Cập nhật invalid_count nếu trước đó phiếu hợp lệ
                if (! $wasInvalid) {
                    $ballot->increment('invalid_count');
                }
            } else {
                // Tạo votes mới theo lựa chọn
                foreach ($this->editSelectedCandidates as $number) {
                    $index = $number - 1;
                    if ($candidates->has($index)) {
                        Vote::create([
                            'ballot_id' => $ballot->id,
                            'candidate_id' => $candidates->get($index)->id,
                            'entry_number' => $this->editingEntryNumber,
                            'is_invalid' => false,
                        ]);
                    }
                }

                // Cập nhật invalid_count nếu trước đó phiếu không hợp lệ
                if ($wasInvalid && $ballot->invalid_count > 0) {
                    $ballot->decrement('invalid_count');
                }
            }
        });

        // Reset state
        $this->cancelEdit();

        // Refresh computed
        unset($this->ballotEntries);
        unset($this->myBallots);
        unset($this->mySummary);

        session()->flash('status', '✅ Đã cập nhật phiếu thành công!');
    }

    /**
     * Hủy chỉnh sửa.
     */
    public function cancelEdit(): void
    {
        $this->editingBallotId = null;
        $this->editingEntryNumber = null;
        $this->editSelectedCandidates = [];
        $this->editIsInvalid = false;
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

            // Reset viewing state if was viewing this ballot
            if ($this->viewingBallotId === $ballotId) {
                $this->viewingBallotId = null;
                $this->cancelEdit();
            }

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
