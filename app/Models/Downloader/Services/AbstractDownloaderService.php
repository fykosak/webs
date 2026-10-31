<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use Fykosak\FKSDBDownloaderCore\Downloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

abstract class AbstractDownloaderService extends AbstractJSONService
{
    protected readonly Cache $cache;

    public function __construct(
        protected readonly string $expiration,
        Storage $storage,
        public readonly Downloader $downloader,
    ) {
        $this->cache = new Cache($storage, static::class);
    }

    /**
     * @throws \Throwable
     * @phpstan-template TModel of object
     * @phpstan-param class-string<TModel> $modelClassName
     * @phpstan-return ($asArray is true ? TModel[] : TModel)
     */
    protected function getRequestAsClass(
        Request $request,
        array $path,
        string $modelClassName,
        bool $asArray = false,
        ?string $explicitExpiration = null
    ): array|object {
        return $this->cache->load(
            $request->getCacheKey() . '_' . implode('.', $path),
            function (&$dependencies) use ($request, $path, $modelClassName, $asArray, $explicitExpiration) {
                $dependencies[Cache::Expire] = $explicitExpiration ?? $this->expiration;
                $json = $this->downloader->download($request);
                foreach ($path as $pathItem) {
                    $json = $json[$pathItem];
                }

                return $this->mapJsonToClass($json, $asArray, $modelClassName);
            }
        );
    }
}
