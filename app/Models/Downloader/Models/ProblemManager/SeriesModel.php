<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\ProblemManager;

use DateTime;

class SeriesModel
{
    public int $seriesId;
    public int $contestYearId;
    public string $label;

    public ?string $release;
    public ?string $deadline;

    /**
     * @var ProblemModel[]
     */
    public array $problems;

    /**
     * @var string[]
     */
    public ?array $serialTopic = null;


    /**
     * @var array {
     *      contestYearId: int,
     *      contestId: int,
     *      year: int,
     * }
     */
    public array $contestYear;

    /**
     * @throws \Exception
     */
    public function getRelease(): ?DateTime
    {
        if ($this->release) {
            return new DateTime($this->release);
        }
        return null;
    }

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
}
