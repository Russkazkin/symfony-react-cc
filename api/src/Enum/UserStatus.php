<?php

namespace App\Enum;

enum UserStatus: string
{
    case Blocked = 'заблокирован';
    case Active = 'активен';
    case Pending = 'ожидает активации email';
}