<?php

declare(strict_types=1);

namespace App\Models\Problems;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class SeriesRequest implements Request
{
    protected string $contest;
    protected int $year;
    protected int $series;

    public function __construct(string $contest, int $year, int $series)
    {
        $this->contest = $contest;
        $this->year = $year;
        $this->series = $series;
    }

    public function getCacheKey(): string
    {
        return sprintf('series.%s.%d.%d', $this->contest, $this->year, $this->series);
    }

    public function getParams(): array
    {
        return [];
    }

    final public function getMethod(): string
    {
        return sprintf('series.json');
    }
}
