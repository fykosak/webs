<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use App\Models\Downloader\Downloaders\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Storage;

final class DummyService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, FKSDBDownloader $downloader)
    {
        parent::__construct($expiration, $storage, $downloader);
    }

    /**
     * @phpstan-template TModel of object
     * @phpstan-param class-string<TModel> $model
     * @throws \Throwable
     * @phpstan-return TModel[]
     */
    public function get(Request $request, string $model, ?string $explicitExpiration = null): array
    {
        return $this->getItem($request, [], $model, true, $explicitExpiration);
    }

    /**
     * @throws \Throwable
     * @phpstan-template TModel of object
     * @phpstan-param class-string<TModel> $model
     * @phpstan-return TModel
     */
    public function getFlat(Request $request, string $model, ?string $explicitExpiration = null): object
    {
        return $this->getItem($request, [], $model, false, $explicitExpiration);
    }
}
