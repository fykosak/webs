<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class ContestsRequest implements Request
{

    public function getMethod(): string
    {
        return 'contests';
    }

    public function getParams(): array
    {
        return [];
    }

    public function getCacheKey(): string
    {
        return 'contests-list';
    }
}
