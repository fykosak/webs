<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Nette\Caching\Storage;

class DummyService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, FKSDBDownloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

    /**
     * @throws \Throwable
     */
    public function get(Request $request, string $model, ?string $explicitExpiration = null)
    {
        return $this->getItem($request, [], $model, true, $explicitExpiration);
    }
}
