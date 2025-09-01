<?php

declare(strict_types=1);

namespace App\Models\Downloader\Downloaders;

use Fykosak\FKSDBDownloaderCore\BasicDownloader;
use Fykosak\FKSDBDownloaderCore\DownloaderException;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;
use Nette\Utils\DateTime;

abstract class NetteDownloader extends BasicDownloader
{
    use SmartObject;

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
        parent::__construct(
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
        $data = $this->cache->load($request->getCacheKey() . '-json');

        if (!$data || $data['expire'] < time()) {
            try {
                $newData = parent::download($request);
            } catch (DownloaderException) {
                $newData = null;
            }

            if ($newData !== null) {
                // if new data is successfully downloaded
                $this->cache->save($request->getCacheKey() . '-json', [
                    'expire' => DateTime::from($explicitExpiration ?? $this->expiration)->format('U'),
                    'data' => $newData
                ]);
                $data = ['data' => $newData];
            } elseif ($data) {
                // if we have at least old data
                $this->cache->save($request->getCacheKey() . '-json', [
                    'expire' => DateTime::from($explicitExpiration ?? $this->expiration)->format('U'),
                    'data' => $data['data']
                ]);
            } else {
                // if no data is available
                throw new DownloaderException("Downloader failed to download data");
            }
        }

        return $data['data'];
    }
}
