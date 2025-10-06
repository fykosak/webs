<?php

declare(strict_types=1);

namespace App\Models\Downloader\Downloaders;

use Fykosak\FKSDBDownloaderCore\BasicDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class FKSDBDownloader extends BasicDownloader
{
    use CachedDownloaderTrait;

    public function __construct(
        string $jsonApiUrl,
        string $username,
        #[\SensitiveParameter]
        string $password,
        string $expiration,
        Storage $storage
    ) {
        $this->cache = new Cache($storage, self::class);
        $this->expiration = $expiration;
        parent::__construct(
            $jsonApiUrl,
            $username,
            $password,
        );
    }

    public function download(Request $request, ?string $explicitExpiration = null): array
    {
        return $this->downloadCached($request, $explicitExpiration);
    }
}
