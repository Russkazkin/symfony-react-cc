<?php

namespace App\Enum;

enum ModerationRecordStatus: string
{
    case Publish = 'опубликовать';
    case Reject = 'отправить на доработку';
}
