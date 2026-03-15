<?php

use App\Livewire\Counting\Reporter;
use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'vote_counter']);
    $this->election = Election::factory()->create();
    $this->position = Position::factory()->for($this->election)->create(['max_votes' => 3]);
    $this->candidates = Candidate::factory(3)->for($this->position)->sequence(
        ['sort_order' => 0, 'name' => 'Ứng viên A'],
        ['sort_order' => 1, 'name' => 'Ứng viên B'],
        ['sort_order' => 2, 'name' => 'Ứng viên C'],
    )->create();

    $this->ballot = Ballot::factory()->for($this->position)->create([
        'election_id' => $this->election->id,
        'user_id' => $this->user->id,
        'expected_count' => 10,
        'entered_count' => 3,
        'invalid_count' => 0,
        'counted_at' => now(),
    ]);

    // Tạo 3 entries với entry_number
    Vote::create(['ballot_id' => $this->ballot->id, 'candidate_id' => $this->candidates[0]->id, 'entry_number' => 1]);
    Vote::create(['ballot_id' => $this->ballot->id, 'candidate_id' => $this->candidates[1]->id, 'entry_number' => 1]);
    Vote::create(['ballot_id' => $this->ballot->id, 'candidate_id' => $this->candidates[0]->id, 'entry_number' => 2]);
    Vote::create(['ballot_id' => $this->ballot->id, 'candidate_id' => $this->candidates[2]->id, 'entry_number' => 3]);
});

test('reporter can view ballot entries', function () {
    $this->actingAs($this->user);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->assertSet('viewingBallotId', $this->ballot->id);
});

test('reporter can start editing an entry', function () {
    $this->actingAs($this->user);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->call('startEdit', $this->ballot->id, 1)
        ->assertSet('editingBallotId', $this->ballot->id)
        ->assertSet('editingEntryNumber', 1)
        ->assertSet('editIsInvalid', false)
        ->assertSet('editSelectedCandidates', [1, 2]); // Entry 1 has candidate[0] (sort_order 0 -> #1) and candidate[1] (sort_order 1 -> #2)
});

test('reporter can save edited entry with new candidates', function () {
    $this->actingAs($this->user);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->call('startEdit', $this->ballot->id, 1)
        ->set('editSelectedCandidates', [2, 3]) // Đổi sang Ứng viên B và C
        ->call('saveEdit')
        ->assertHasNoErrors();

    // Entry 1 giờ chỉ có candidate[1] và candidate[2]
    $entryVotes = Vote::where('ballot_id', $this->ballot->id)
        ->where('entry_number', 1)
        ->get();

    expect($entryVotes)->toHaveCount(2);
    expect($entryVotes->pluck('candidate_id')->sort()->values()->toArray())
        ->toBe([$this->candidates[1]->id, $this->candidates[2]->id]);
});

test('reporter can mark entry as invalid', function () {
    $this->actingAs($this->user);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->call('startEdit', $this->ballot->id, 1)
        ->call('toggleEditInvalid')
        ->call('saveEdit')
        ->assertHasNoErrors();

    // Entry 1 giờ là phiếu không hợp lệ
    $entryVotes = Vote::where('ballot_id', $this->ballot->id)
        ->where('entry_number', 1)
        ->get();

    expect($entryVotes)->toHaveCount(1);
    expect($entryVotes->first()->is_invalid)->toBeTrue();

    // invalid_count phải tăng
    expect($this->ballot->fresh()->invalid_count)->toBe(1);
});

test('reporter can change invalid entry back to valid', function () {
    $this->actingAs($this->user);

    // Tạo phiếu không hợp lệ
    Vote::create([
        'ballot_id' => $this->ballot->id,
        'candidate_id' => $this->candidates[0]->id,
        'entry_number' => 4,
        'is_invalid' => true,
    ]);
    $this->ballot->update(['entered_count' => 4, 'invalid_count' => 1]);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->call('startEdit', $this->ballot->id, 4)
        ->assertSet('editIsInvalid', true)
        ->call('toggleEditInvalid') // Chuyển về hợp lệ
        ->set('editSelectedCandidates', [1, 2])
        ->call('saveEdit')
        ->assertHasNoErrors();

    // Entry 4 giờ có votes hợp lệ
    $entryVotes = Vote::where('ballot_id', $this->ballot->id)
        ->where('entry_number', 4)
        ->where('is_invalid', false)
        ->get();

    expect($entryVotes)->toHaveCount(2);

    // invalid_count phải giảm
    expect($this->ballot->fresh()->invalid_count)->toBe(0);
});

test('reporter cannot edit ballot of another user', function () {
    $otherUser = User::factory()->create(['role' => 'vote_counter']);

    $this->actingAs($otherUser);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('startEdit', $this->ballot->id, 1)
        ->assertSet('editingBallotId', null); // Không set vì ballot không thuộc user
});

test('reporter cancel edit resets state', function () {
    $this->actingAs($this->user);

    Livewire::test(Reporter::class, ['election' => $this->election])
        ->call('viewEntries', $this->ballot->id)
        ->call('startEdit', $this->ballot->id, 1)
        ->call('cancelEdit')
        ->assertSet('editingBallotId', null)
        ->assertSet('editingEntryNumber', null)
        ->assertSet('editSelectedCandidates', []);
});
