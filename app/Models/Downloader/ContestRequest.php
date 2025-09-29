<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final class ContestRequest implements Request
{
    public function __construct(private readonly int $contestId)
    {
    }

    public function getMethod(): string
    {
        return 'contests/' . $this->contestId;
    }

    public function getParams(): array
    {
        return [
            'contestId' => $this->contestId,
        ];
    }

    public function getCacheKey(): string
    {
        return sprintf('contest.%d', $this->contestId);
    }
}
