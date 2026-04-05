<?php

declare(strict_types=1);

namespace App\Models\Downloader\Downloaders;

use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Fykosak\FKSDBDownloaderCore\KeyDownloader;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

class ProblemManagerDownloader extends KeyDownloader
{
    use CachedDownloaderTrait;

    public function __construct(
        string $url,
        string $apiKey,
        string $expiration,
        Storage $storage
    ) {
        $this->cache = new Cache($storage, self::class);
        $this->expiration = $expiration;
        parent::__construct($url, $apiKey);
    }

    public function download(Request $request): array
    {
        return $this->downloadCached($request);
    }
}
