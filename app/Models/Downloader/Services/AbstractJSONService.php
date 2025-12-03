<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use Fykosak\FKSDBDownloaderCore\Downloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

abstract class AbstractJSONService
{
    use SmartObject;

    protected Downloader $downloader;
    protected readonly Cache $cache;
    protected readonly string $expiration;

    public function __construct(string $expiration, Storage $storage)
    {
        $this->cache = new Cache($storage, static::class);
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
    ): mixed {
        return $this->cache->load(
            $request->getCacheKey() . '_' . implode('.', $path),
            function (&$dependencies) use ($request, $path, $modelClassName, $asArray, $explicitExpiration) {
                $dependencies[Cache::EXPIRE] = $explicitExpiration ?? $this->expiration;
                $json = $this->downloader->download($request);
                foreach ($path as $pathItem) {
                    $json = $json[$pathItem];
                }

                $mapper = new \JsonMapper();
                $mapper->bEnforceMapType = false;
                if ($asArray) {
                    return $mapper->mapArray(
                        array_map(function (array $datum) {
                            return self::toStd($datum);
                        }, $json),
                        [],
                        $modelClassName
                    );
                } else {
                    return $mapper->map((object)$json, new $modelClassName());
                }
            }
        );
    }

    public static function toStd(array $datum): \stdClass
    {
        $newJson = new \stdClass();
        foreach ($datum as $key => $value) {
            if (is_array($value)) {
                $newJson->$key = self::toStd($value);
            } else {
                $newJson->$key = $value;
            }
        }
        return $newJson;
    }
}
