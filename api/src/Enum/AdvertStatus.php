<?php

namespace App\Enum;

enum AdvertStatus: string
{
    case Draft = 'черновик';
    case Moderation = 'на модерации';
    case Rejected = 'отклонено, к доработке';
    case Completed = 'снято, продано';
    case Active = 'активно';
}