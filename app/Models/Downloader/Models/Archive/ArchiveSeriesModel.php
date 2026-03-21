<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\Archive;

use App\Models\Downloader\Models\Core\SeriesModel;
use DateTime;

class ArchiveSeriesModel extends SeriesModel
{
    public ?string $deadline;
    public int $year;
    public int $series;
    /**
     * @var int[]
     */
    public array $problems;

    /**
     * @throws \Exception
     */
    public function getDeadline(): ?DateTime
    {
        if ($this->deadline) {
            return new DateTime($this->deadline);
        }
        return null;
    }

    public function getLabel(): string
    {
        return (string)$this->series;
    }

    public function getYear(): int
    {
        return $this->year;
    }
}
