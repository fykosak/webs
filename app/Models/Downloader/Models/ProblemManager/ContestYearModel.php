<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\ProblemManager;

class ContestYearModel
{
    public int $contestYearId;
    public int $contestId;
    public int $year;

    /**
     * @phpstan-var array<array{
     *      seriesId: int,
     *      contestYearId: int,
     *      label: string,
     *      release: string,
     *      deadline: string
     * }>
     */
    public array $series;
}
