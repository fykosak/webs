<?php

declare(strict_types=1);

namespace App\Models\Problems;
use Nette\Caching\Cache;

use Fykosak\NetteFKSDBDownloader\ORM\Services\AbstractJSONService;

final class SeriesService extends AbstractJSONService
{
    public function getSeries(
        string $contest,
        int $year,
        int $series,
        ?string $explicitExpiration = null
    ): SeriesModel {
        return $this->getItem(
            new SeriesRequest($contest, $year, $series),
            [(string)$year, (string)$series],
            SeriesModel::class,
            false,
            $explicitExpiration
        );
    }

    public function getLatestSeries(string $contest): SeriesModel {
        return $this->cache->load(
            sprintf("lastSeries_%s", $contest),
            function (&$dependencies) use ($contest) {
                $dependencies[Cache::EXPIRE] = $this->expiration;
                $jsonText = $this->downloader->download(new SeriesRequest($contest, 0, 0)); // 0, 0 are dummy data
                $json = json_decode($jsonText);

                $yearArray = get_object_vars($json);
                $year = end($yearArray);

                $seriesArray = get_object_vars($year);
                $series = end($seriesArray);

                $mapper = new \JsonMapper();
                return $mapper->map($series, new SeriesModel());
            }
        );
    }
}
