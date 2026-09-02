<?php

declare(strict_types=1);

namespace App\Models\Downloader\Requests\ProblemManager;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final readonly class ContestYearRequest implements Request
{
    public function __construct(public int $contestId)
    {
    }

    public function getMethod(): string
    {
        return 'contest/' . $this->contestId . '/years';
    }

    public function getParams(): array
    {
        return [];
    }

    public function getCacheKey(): string
    {
        return sprintf('pm.contest.%d', $this->contestId);
    }
}
