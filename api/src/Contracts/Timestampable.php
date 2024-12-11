<?php

namespace App\Contracts;

use DateTimeImmutable;

interface Timestampable
{
    public function getCreatedAt(): ?DateTimeImmutable;

    public function setCreatedAt(): self;

    public function getUpdatedAt(): ?DateTimeImmutable;

    public function setUpdatedAt(): self;
}