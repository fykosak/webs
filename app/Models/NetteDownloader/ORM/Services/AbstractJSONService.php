<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

abstract class AbstractJSONService
{
    use SmartObject;

    protected FKSDBDownloader $downloader;
    protected Cache $cache;
    protected string $expiration;

    public function __construct(string $expiration, FKSDBDownloader $downloader, Storage $storage)
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
            $request->getCacheKey() . '_' . implode('.', $path),
            function (&$dependencies) use ($request, $path, $modelClassName, $asArray, $explicitExpiration) {
                $dependencies[Cache::EXPIRE] = $explicitExpiration ?? $this->expiration;
                $json = $this->downloader->download($request);
                foreach ($path as $pathItem) {
                    $json = $json[$pathItem];
                }

                $mapper = new \JsonMapper();
                if ($asArray) {

                    return $mapper->mapArray(
                        array_map(function (array $datum) {
                            return self::toStd($datum);
                        }, $json),
                        [],
                        $modelClassName
                    );
                } else {
                    return $mapper->map($json, new $modelClassName());
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
