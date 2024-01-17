<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class SeriesRequest implements Request
{
    protected string $contest;
    protected int $year;

    public function __construct(string $contest, int $year)
    {
        $this->contest = $contest;
        $this->year = $year;
    }

    public function getCacheKey(): string
    {
        return sprintf('series.%s.%d', $this->contest, $this->year);
    }

    public function getParams(): array
    {
        return [];
    }

    final public function getMethod(): string
    {
        return sprintf('%s/%d/series.json', $this->contest, $this->year);
    }
}
