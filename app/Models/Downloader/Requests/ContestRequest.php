<?php

declare(strict_types=1);

namespace App\Models\Downloader\Requests;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final readonly class ContestRequest implements Request
{
    public function __construct(private int $contestId)
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
