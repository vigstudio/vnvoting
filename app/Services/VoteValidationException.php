<?php

namespace App\Services;

use Exception;

class VoteValidationException extends Exception
{
    public static function invalidFormat(string $input): self
    {
        return new self("Định dạng không hợp lệ: '{$input}'. Vui lòng nhập số ngăn cách bằng dấu phẩy (ví dụ: 1,2,3)");
    }

    public static function duplicateSelection(): self
    {
        return new self('Bạn đã chọn ứng viên nhiều lần. Mỗi ứng viên chỉ được chọn 1 lần.');
    }

    public static function invalidCandidates(array $invalidNumbers): self
    {
        $numbers = implode(', ', $invalidNumbers);
        return new self("Số ứng viên không hợp lệ: {$numbers}. Vui lòng chỉ chọn số trong danh sách.");
    }

    public static function noCandidates(): self
    {
        return new self('Vui lòng chọn ít nhất một ứng viên.');
    }

    public static function tooManyCandidates(int $max, int $selected): self
    {
        return new self("Bạn chỉ được chọn tối đa {$max} ứng viên. Bạn đã chọn {$selected} ứng viên.");
    }

    public static function ballotFull(int $expectedCount): self
    {
        return new self("Lô phiếu này đã nhập đủ {$expectedCount} phiếu. Không thể nhập thêm.");
    }

    public static function thresholdOutOfRange(float $percentage): self
    {
        return new self("Số phiếu nhập ({$percentage}%) nằm ngoài khoảng cho phép (50-150%).");
    }
}
