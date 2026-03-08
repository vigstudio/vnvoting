<?php

use App\Services\VoteCounter;
use App\Services\VoteValidationException;
use App\Models\Position;
use App\Models\Election;
use App\Models\Ballot;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Tạo election, position, ballot cho tests
    $this->election = Election::factory()->create();
    $this->position = Position::factory()->create([
        'election_id' => $this->election->id,
        'max_votes' => 2,
    ]);

    // Tạo 3 candidates
    $this->candidates = [
        \App\Models\Candidate::factory()->create([
            'position_id' => $this->position->id,
            'name' => 'Ứng viên 1',
            'sort_order' => 0,
        ]),
        \App\Models\Candidate::factory()->create([
            'position_id' => $this->position->id,
            'name' => 'Ứng viên 2',
            'sort_order' => 1,
        ]),
        \App\Models\Candidate::factory()->create([
            'position_id' => $this->position->id,
            'name' => 'Ứng viên 3',
            'sort_order' => 2,
        ]),
    ];

    $this->ballot = Ballot::factory()->create([
        'election_id' => $this->election->id,
        'position_id' => $this->position->id,
        'expected_count' => 10,
        'entered_count' => 0,
    ]);

    $this->counter = app(VoteCounter::class);
});

test('parseBallotInput phân tích đúng định dạng chuẩn', function () {
    $result = $this->counter->parseBallotInput('1,2,3');

    expect($result)->toBe([1, 2, 3]);
});

test('parseBallotInput xử lý khoảng trắng', function () {
    $result = $this->counter->parseBallotInput(' 1 , 2 , 3 ');

    expect($result)->toBe([1, 2, 3]);
});

test('parseBallotInput loại bỏ trùng lặp', function () {
    $result = $this->counter->parseBallotInput('1,2,2,3');

    expect($result)->toBe([1, 2, 3]);
});

test('parseBallotInput lỗi với định dạng không hợp lệ', function () {
    $this->expectException(VoteValidationException::class);

    $this->counter->parseBallotInput('1,abc,3');
});

test('validateBallot cho phép lựa chọn hợp lệ', function () {
    expect(fn () => $this->counter->validateBallot([1, 2], $this->position))
        ->not->toThrow(VoteValidationException::class);
});

test('validateBallot lỗi khi không chọn ứng viên nào', function () {
    $this->expectException(VoteValidationException::class);

    $this->counter->validateBallot([], $this->position);
});

test('validateBallot lỗi khi chọn quá số tối đa', function () {
    $this->expectException(VoteValidationException::class);

    $this->counter->validateBallot([1, 2, 3], $this->position);
});

test('validateBallot lỗi với số ứng viên không hợp lệ', function () {
    $this->expectException(VoteValidationException::class);

    $this->counter->validateBallot([1, 5], $this->position);
});

test('recordBallot ghi nhận phiếu thành công', function () {
    $this->counter->recordBallot('1,2', $this->ballot);

    $this->ballot->refresh();

    expect($this->ballot->entered_count)->toBe(1);

    // Kiểm tra votes được tạo
    expect(\App\Models\Vote::where('ballot_id', $this->ballot->id)->count())
        ->toBe(2);
});

test('checkThreshold trả về đúng khi trong ngưỡng', function () {
    $this->ballot->update(['entered_count' => 10]); // 100%

    $result = $this->counter->checkThreshold($this->ballot);

    expect($result['within_threshold'])->toBeTrue();
    expect($result['percentage'])->toBe(100.0);
});

test('checkThreshold trả về đúng khi dưới ngưỡng', function () {
    $this->ballot->update(['entered_count' => 4]); // 40%

    $result = $this->counter->checkThreshold($this->ballot);

    expect($result['within_threshold'])->toBeFalse();
    expect($result['percentage'])->toBe(40.0);
});

test('checkThreshold trả về đúng khi trên ngưỡng', function () {
    $this->ballot->update(['entered_count' => 16]); // 160%

    $result = $this->counter->checkThreshold($this->ballot);

    expect($result['within_threshold'])->toBeFalse();
    expect($result['percentage'])->toBe(160.0);
});

test('finalizeBallot hoàn thành khi trong ngưỡng', function () {
    $this->ballot->update(['entered_count' => 10]);

    expect(fn () => $this->counter->finalizeBallot($this->ballot))
        ->not->toThrow(VoteValidationException::class);

    $this->ballot->refresh();

    expect($this->ballot->counted_at)->not->toBeNull();
});

test('finalizeBallot lỗi khi ngoài ngưỡng', function () {
    $this->ballot->update(['entered_count' => 20]); // 200%

    $this->expectException(VoteValidationException::class);

    $this->counter->finalizeBallot($this->ballot);
});
