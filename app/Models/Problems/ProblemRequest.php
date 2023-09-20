<?php

declare(strict_types=1);

namespace App\Models\Problems;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class ProblemRequest implements Request
{
    protected string $contest;
    protected int $year;
    protected int $series;
    protected int $number;

    public function __construct(string $contest, int $year, int $series, int $number)
    {
        $this->contest = $contest;
        $this->year = $year;
        $this->series = $series;
        $this->number = $number;
    }

    public function getCacheKey(): string
    {
        return sprintf('problem.%s.%d.%d.%d', $this->contest, $this->year, $this->series, $this->number);
    }

    public function getParams(): array
    {
        return [];
    }

    final public function getMethod(): string
    {
        return sprintf('problem%d-%d.json', $this->series, $this->number);
    }
}
