<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

use Fykosak\Utils\DateTime\Period;

final class GameModel
{
    public readonly ?array $availablePoints;
    public readonly ?int $tasksOnBoard;
    public readonly bool $hardVisible;
    public readonly \DateTimeImmutable $begin;
    public readonly \DateTimeImmutable $end;

    public function getGamePeriod(): Period
    {
        return new Period($this->begin, $this->end);
    }
}
