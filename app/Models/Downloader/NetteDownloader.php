<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Downloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

abstract class NetteDownloader
{
    use SmartObject;

    private Downloader $downloader;
    private Cache $cache;

    public function __construct(
        string $jsonApiUrl,
        string $username,
        #[\SensitiveParameter]
        string $password,
        private readonly string $expiration,
        Storage $storage
    ) {
        $this->cache = new Cache($storage, self::class);
        $this->downloader = new Downloader(
            $jsonApiUrl,
            $username,
            $password,
        );
    }

    /**
     * @throws \Throwable
     */
    public function download(Request $request, ?string $explicitExpiration = null): array
    {
        return $this->cache->load(
            $request->getCacheKey() . '-json',
            function (&$dependencies) use ($request, $explicitExpiration): array {
                $dependencies[Cache::EXPIRE] = $explicitExpiration ?? $this->expiration;
                return $this->downloader->download($request);
            }
        );
    }
}
