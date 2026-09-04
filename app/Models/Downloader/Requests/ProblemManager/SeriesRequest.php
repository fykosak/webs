<?php

declare(strict_types=1);

namespace App\Models\Downloader\Requests\ProblemManager;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final readonly class SeriesRequest implements Request
{
    public function __construct(public int $seriesId)
    {
    }

    public function getMethod(): string
    {
        return 'series/' . $this->seriesId;
    }

    public function getParams(): array
    {
        return [];
    }

    public function getCacheKey(): string
    {
        return sprintf('pm.series.%d', $this->seriesId);
    }
}
