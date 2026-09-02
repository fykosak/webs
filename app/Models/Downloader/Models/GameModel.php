<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

use Fykosak\Utils\DateTime\Period;

final readonly class GameModel
{
    public ?array $availablePoints;
    public ?int $tasksOnBoard;
    public bool $hardVisible;
    public \DateTimeImmutable $begin;
    public \DateTimeImmutable $end;

    public function getGamePeriod(): Period
    {
        return new Period($this->begin, $this->end);
    }
}
