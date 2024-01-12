<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Downloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

final class FKSDBDownloader
{
    use SmartObject;

    private Downloader $downloader;
    private string $username;
    private string $password;
    private string $expiration;
    private string $jsonApiUrl;
    private Cache $cache;

    public function __construct(
        string $jsonApiUrl,
        string $username,
        string $password,
        string $expiration,
        Storage $storage
    ) {
        $this->cache = new Cache($storage, self::class);
        $this->username = $username;
        $this->password = $password;
        $this->jsonApiUrl = $jsonApiUrl;
        $this->expiration = $expiration;
    }

    public function getDownloader(): Downloader
    {
        if (!isset($this->downloader)) {
            $this->downloader = new Downloader([
                'fksdb' => [
                    'url' => $this->jsonApiUrl,
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]);
        }
        return $this->downloader;
    }

    /**
     * @throws \Throwable
     */
    public function download(string $app, Request $request, ?string $explicitExpiration = null): array
    {
        return $this->cache->load(
            $request->getCacheKey() . '-json',
            function (&$dependencies) use ($request, $explicitExpiration, $app): array {
                $dependencies[Cache::EXPIRE] = $explicitExpiration ?? $this->expiration;
                return $this->getDownloader()->download($app, $request);
            }
        );
    }
}
