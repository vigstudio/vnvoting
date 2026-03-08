<?php

use App\Exports\ElectionResultsExport;
use App\Exports\PositionResultsExport;
use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\User;
use App\Models\Vote;
use App\Services\VoteCounter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->voteCounter = User::factory()->create(['role' => 'vote_counter']);
});

test('admin can download excel export', function () {
    $now = now();
    \Carbon\Carbon::setTestNow($now);

    $election = Election::factory()->create(['title' => 'Test Election 2026']);
    $position = Position::factory()->for($election)->create([
        'title' => 'Chủ tịch',
        'ballot_color' => '#FF0000',
        'max_votes' => 1,
    ]);

    $candidates = Candidate::factory(2)->for($position)->sequence(
        ['sort_order' => 0, 'name' => 'Nguyen Van A'],
        ['sort_order' => 1, 'name' => 'Tran Thi B'],
    )->create();

    // Tạo nhiều ballots để test export
    $ballot1 = Ballot::factory()->for($position)->create(['entered_count' => 10]);
    $ballot2 = Ballot::factory()->for($position)->create(['entered_count' => 10]);
    $ballot3 = Ballot::factory()->for($position)->create(['entered_count' => 10]);

    // Tạo votes - mỗi ballot vote cho 1 candidate
    Vote::create(['ballot_id' => $ballot1->id, 'candidate_id' => $candidates[0]->id]);
    Vote::create(['ballot_id' => $ballot2->id, 'candidate_id' => $candidates[0]->id]);
    Vote::create(['ballot_id' => $ballot3->id, 'candidate_id' => $candidates[1]->id]);

    Excel::fake();

    actingAs($this->admin)
        ->get(route('admin.elections.export.excel', $election))
        ->assertStatus(200);

    $expectedFilename = 'ket-qua-bau-cu-test-election-2026-' . $now->format('Ymd-His') . '.xlsx';
    Excel::assertDownloaded($expectedFilename);
});

test('vote_counter can download excel export', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();

    Candidate::factory(2)->for($position)->create();
    Ballot::factory()->for($position)->create();

    Excel::fake();

    actingAs($this->voteCounter)
        ->get(route('counting.export.excel', $election))
        ->assertStatus(200);
});

test('admin can download pdf export', function () {
    $election = Election::factory()->create(['title' => 'Test PDF Election']);
    $position = Position::factory()->for($election)->create([
        'title' => 'Bí thư',
        'ballot_color' => '#00FF00',
    ]);

    Candidate::factory(2)->for($position)->create();
    Ballot::factory()->for($position)->create(['entered_count' => 50]);

    actingAs($this->admin)
        ->get(route('admin.elections.export.pdf', $election))
        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');
});

test('vote_counter can download pdf export', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create();

    Candidate::factory(2)->for($position)->create();
    Ballot::factory()->for($position)->create();

    actingAs($this->voteCounter)
        ->get(route('counting.export.pdf', $election))
        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');
});

test('export creates multiple sheets for multiple positions', function () {
    $election = Election::factory()->create();

    $position1 = Position::factory()->for($election)->create(['title' => 'Position 1', 'sort_order' => 1]);
    $position2 = Position::factory()->for($election)->create(['title' => 'Position 2', 'sort_order' => 2]);
    $position3 = Position::factory()->for($election)->create(['title' => 'Position 3', 'sort_order' => 3]);

    Candidate::factory(2)->for($position1)->create();
    Candidate::factory(2)->for($position2)->create();
    Candidate::factory(2)->for($position3)->create();

    $counter = app(VoteCounter::class);
    $export = new ElectionResultsExport($election, $counter);

    expect($export->sheets())->toHaveCount(3);

    expect($export->sheets()[0]->title())->toBe('Position 1');
    expect($export->sheets()[1]->title())->toBe('Position 2');
    expect($export->sheets()[2]->title())->toBe('Position 3');
});

test('position export includes correct data', function () {
    $election = Election::factory()->create();
    $position = Position::factory()->for($election)->create([
        'title' => 'Test Position',
        'ballot_color' => '#FF5733',
        'max_votes' => 2,
    ]);

    $candidates = Candidate::factory(2)->for($position)->sequence(
        ['sort_order' => 0, 'name' => 'Candidate A'],
        ['sort_order' => 1, 'name' => 'Candidate B'],
    )->create();

    // Tạo nhiều ballots - mỗi ballot có votes riêng
    $ballots = Ballot::factory(30)->for($position)->create(['entered_count' => 1]);

    // 21 ballots cho Candidate A, 9 ballots cho Candidate B
    for ($i = 0; $i < 21; $i++) {
        Vote::create([
            'ballot_id' => $ballots[$i]->id,
            'candidate_id' => $candidates[0]->id,
        ]);
    }
    for ($i = 21; $i < 30; $i++) {
        Vote::create([
            'ballot_id' => $ballots[$i]->id,
            'candidate_id' => $candidates[1]->id,
        ]);
    }

    $counter = app(VoteCounter::class);
    $export = new PositionResultsExport($position, $counter);

    $data = $export->array();

    expect($data[0][1])->toBe('Test Position');
    expect($data[1][1])->toBe('#FF5733');

    expect($data[5][0])->toBe('STT');
    expect($data[5][1])->toBe('Tên ứng viên');

    expect($data[6][0])->toBe(1);
    expect($data[6][1])->toBe('Candidate A');
    expect($data[6][2])->toBe(21);

    expect($data[7][0])->toBe(2);
    expect($data[7][1])->toBe('Candidate B');
    expect($data[7][2])->toBe(9);

    expect($data[9][0])->toBe('TỔNG SỐ PHIẾU:');
    expect($data[9][1])->toBe(30);
});
