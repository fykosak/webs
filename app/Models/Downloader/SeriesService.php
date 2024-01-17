<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\NetteDownloader\ORM\Services\AbstractJSONService;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class SeriesService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, ProblemManagerDownloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

    public function getSeries(
        string $contest,
        int $year,
        int $series,
        ?string $explicitExpiration = null
    ): SeriesModel {
        return $this->getItem(
            new SeriesRequest($contest, $year, $series),
            [(string)$series],
            SeriesModel::class,
            false,
            $explicitExpiration
        );
    }

    public function getLatestSeries(string $contest, int $year): int {

        return $this->cache->load(
            sprintf("lastSeries_%s", $contest),
            function (&$dependencies) use ($contest, $year) {
                $dependencies[Cache::EXPIRE] = $this->expiration;
                $json = $this->downloader->download(new SeriesRequest($contest, $year));

                $series = end($json);
                return $series["series"];
            }
        );
    }
}
