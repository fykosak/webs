<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use Fykosak\FKSDBDownloaderCore\Requests\Request;
use App\Models\NetteDownloader\NetteFKSDBDownloader;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

abstract class AbstractJSONService
{
    use SmartObject;

    protected NetteFKSDBDownloader $downloader;
    protected Cache $cache;
    protected string $expiration;

    public function __construct(string $expiration, NetteFKSDBDownloader $downloader, Storage $storage)
    {
        $this->cache = new Cache($storage, static::class);
        $this->downloader = $downloader;
        $this->expiration = $expiration;
    }

    /**
     * @throws \Throwable
     */
    protected function getItem(
        Request $request,
        array $path,
        string $modelClassName,
        bool $asArray = false,
        ?string $explicitExpiration = null
    ) {
        return $this->cache->load(
            $request->getCacheKey() . "_" . implode(".", $path),
            function (&$dependencies) use ($request, $path, $modelClassName, $asArray, $explicitExpiration) {
                $dependencies[Cache::EXPIRE] = $explicitExpiration ?? $this->expiration;
                $jsonText = $this->downloader->download($request);
                $json = json_decode($jsonText);

                foreach ($path as $pathItem) {
                    $json = $json->$pathItem;
                }

                $mapper = new \JsonMapper();
                if ($asArray) {
                    return $mapper->mapArray($json, [], $modelClassName);
                } else {
                    return $mapper->map($json, new $modelClassName());
                }
            }
        );
    }
}
