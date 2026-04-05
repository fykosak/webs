<?php

declare(strict_types=1);

namespace App\Models\Downloader\Downloaders;

use Fykosak\FKSDBDownloaderCore\DownloaderException;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Utils\DateTime;

trait CachedDownloaderTrait
{
    private Cache $cache;
    private string $expiration;

    /**
     * @throws \Throwable
     */
    public function downloadCached(Request $request, ?string $explicitExpiration = null): array
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
