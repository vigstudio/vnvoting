<?php

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Services\VoteCounter;
use App\Services\VoteValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->counter = app(VoteCounter::class);
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->voteCounter = User::factory()->create(['role' => 'vote_counter']);
});

test('parseBallotInput parses valid input correctly', function () {
    $result = $this->counter->parseBallotInput('1,2,3');
    expect($result)->toBe([1, 2, 3]);

    $result = $this->counter->parseBallotInput(' 1 , 2 , 3 ');
    expect($result)->toBe([1, 2, 3]);

    $result = $this->counter->parseBallotInput('3,1,2');
    expect($result)->toBe([3, 1, 2]);
});

test('parseBallotInput removes duplicates', function () {
    $result = $this->counter->parseBallotInput('1,2,2,3,1');
    expect($result)->toBe([1, 2, 3]);
});

test('parseBallotInput throws exception for invalid format', function () {
    $this->counter->parseBallotInput('1,abc,3');
})->throws(VoteValidationException::class, 'Định dạng không hợp lệ');

test('recordBallot records votes correctly', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create(['max_votes' => 3]);

    $candidates = Candidate::factory(3)->for($position)->sequence(
        ['sort_order' => 0],
        ['sort_order' => 1],
        ['sort_order' => 2],
    )->create();

    $ballot = Ballot::factory()->for($position)->create([
        'expected_count' => 50,
        'entered_count' => 0,
    ]);

    $this->counter->recordBallot('1,2', $ballot);

    expect($ballot->fresh()->entered_count)->toBe(1);

    assertDatabaseHas('votes', [
        'ballot_id' => $ballot->id,
        'candidate_id' => $candidates[0]->id,
    ]);

    assertDatabaseHas('votes', [
        'ballot_id' => $ballot->id,
        'candidate_id' => $candidates[1]->id,
    ]);

    assertDatabaseMissing('votes', [
        'ballot_id' => $ballot->id,
        'candidate_id' => $candidates[2]->id,
    ]);
});

test('recordBallot creates separate entries with entry_number', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create(['max_votes' => 2]);

    $candidates = Candidate::factory(2)->for($position)->sequence(
        ['sort_order' => 0],
        ['sort_order' => 1],
    )->create();

    $ballot = Ballot::factory()->for($position)->create([
        'expected_count' => 10,
        'entered_count' => 0,
    ]);

    $this->counter->recordBallot('1,2', $ballot);
    $this->counter->recordBallot('1,2', $ballot);

    expect($ballot->fresh()->entered_count)->toBe(2);

    // Mỗi lần recordBallot tạo votes riêng biệt (unique constraint đã bị xóa)
    expect(Vote::where('ballot_id', $ballot->id)
        ->where('candidate_id', $candidates[0]->id)
        ->count())->toBe(2);

    // Kiểm tra entry_number được set đúng
    expect(Vote::where('ballot_id', $ballot->id)->where('entry_number', 1)->count())->toBe(2);
    expect(Vote::where('ballot_id', $ballot->id)->where('entry_number', 2)->count())->toBe(2);
});

test('recordBallot validates max_votes', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create(['max_votes' => 2]);

    Candidate::factory(3)->for($position)->sequence(
        ['sort_order' => 0],
        ['sort_order' => 1],
        ['sort_order' => 2],
    )->create();

    $ballot = Ballot::factory()->for($position)->create([
        'expected_count' => 50,
        'entered_count' => 0,
    ]);

    $this->counter->recordBallot('1,2,3', $ballot);
})->throws(VoteValidationException::class, 'tối đa');

test('checkThreshold returns correct status', function () {
    $ballot = Ballot::factory()->make([
        'expected_count' => 100,
        'entered_count' => 100,
    ]);

    $result = $this->counter->checkThreshold($ballot);

    expect($result)->toBe([
        'within_threshold' => true,
        'percentage' => 100.0,
        'message' => 'Đúng khoảng cho phép',
    ]);

    $ballot2 = Ballot::factory()->make([
        'expected_count' => 100,
        'entered_count' => 45,
    ]);

    $result2 = $this->counter->checkThreshold($ballot2);

    expect($result2['within_threshold'])->toBeFalse();
    expect($result2['percentage'])->toBe(45.0);
});

test('finalizeBallot sets counted_at when within threshold', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();
    $ballot = Ballot::factory()->for($position)->create([
        'expected_count' => 100,
        'entered_count' => 100,
        'counted_at' => null,
    ]);

    $this->counter->finalizeBallot($ballot);

    expect($ballot->fresh()->counted_at)->not->toBeNull();
});

test('finalizeBallot throws exception when outside threshold', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();
    $ballot = Ballot::factory()->for($position)->create([
        'expected_count' => 100,
        'entered_count' => 45,
    ]);

    $this->counter->finalizeBallot($ballot);
})->throws(VoteValidationException::class, 'khoảng cho phép');

test('getResults returns sorted candidates by vote count', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();

    $candidates = Candidate::factory(3)->for($position)->sequence(
        ['sort_order' => 0, 'name' => 'Nguyen Van A'],
        ['sort_order' => 1, 'name' => 'Tran Thi B'],
        ['sort_order' => 2, 'name' => 'Le Van C'],
    )->create();

    // Tạo nhiều ballots để test sorting
    $ballot1 = Ballot::factory()->for($position)->create();
    $ballot2 = Ballot::factory()->for($position)->create();
    $ballot3 = Ballot::factory()->for($position)->create();

    // Ballot 1: chọn Tran Thi B
    Vote::create(['ballot_id' => $ballot1->id, 'candidate_id' => $candidates[1]->id]);

    // Ballot 2: chọn Tran Thi B
    Vote::create(['ballot_id' => $ballot2->id, 'candidate_id' => $candidates[1]->id]);

    // Ballot 3: chọn Nguyen Van A
    Vote::create(['ballot_id' => $ballot3->id, 'candidate_id' => $candidates[0]->id]);

    // getResults chỉ lấy votes của 1 ballot
    $results = $this->counter->getResults($ballot1);

    // Với 1 ballot, Tran Thi B có 1 phiếu
    expect($results[0]['name'])->toBe('Tran Thi B');
    expect($results[0]['vote_count'])->toBe(1);
});

test('getPositionResults calculates percentages correctly', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();

    $candidates = Candidate::factory(2)->for($position)->sequence(
        ['sort_order' => 0],
        ['sort_order' => 1],
    )->create();

    // Tạo 30 ballots - mỗi ballot cho 1 vote
    $ballots = Ballot::factory(30)->for($position)->create(['entered_count' => 1]);

    // 20 ballots cho candidate 0, 10 ballots cho candidate 1
    for ($i = 0; $i < 20; $i++) {
        Vote::create(['ballot_id' => $ballots[$i]->id, 'candidate_id' => $candidates[0]->id]);
    }
    for ($i = 20; $i < 30; $i++) {
        Vote::create(['ballot_id' => $ballots[$i]->id, 'candidate_id' => $candidates[1]->id]);
    }

    $results = $this->counter->getPositionResults($position);

    expect($results[0]['vote_count'])->toBe(20);
    expect($results[0]['percentage'])->toBe(66.7);
    expect($results[1]['vote_count'])->toBe(10);
    expect($results[1]['percentage'])->toBe(33.3);
});
