<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

final class ContestModel
{
    public int $contestId;
    public string $contest;
    public string $name;
    /** @var ContestYearModel[] */
    public array $years;
}
