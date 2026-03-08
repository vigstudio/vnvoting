<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case VOTE_COUNTER = 'vote_counter';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Quản trị viên',
            self::VOTE_COUNTER => 'Kiểm phiếu viên',
        };
    }

    public function is(UserRole $role): bool
    {
        return $this === $role;
    }
}
