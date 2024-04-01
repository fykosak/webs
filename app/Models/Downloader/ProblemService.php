<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\NetteDownloader\ORM\Services\AbstractJSONService;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class ProblemService extends AbstractJSONService
{
    private string $problemManagerURL;

    public function __construct(
        string $expiration,
        string $problemManagerURL,
        Storage $storage,
        ProblemManagerDownloader $downloader
    ) {
        $this->downloader = $downloader;
        $this->problemManagerURL = $problemManagerURL;
        parent::__construct($expiration, $storage);
    }

    /**
     * @throws \Throwable
     */
    public function getProblem(
        string $contest,
        int $year,
        int $series,
        int $number,
        ?string $explicitExpiration = null
    ): ProblemModel {
        return $this->getItem(
            new ProblemRequest($contest, $year, $series, $number),
            [],
            ProblemModel::class,
            false,
            $explicitExpiration
        );
    }

    /**
     * @throws \Throwable
     */
    public function getSeries(
        string $contest,
        int $year,
        int $series,
        ?string $explicitExpiration = null
    ): SeriesModel {
        return $this->getItem(
            new SeriesRequest($contest, $year),
            [(string)$series],
            SeriesModel::class,
            false,
            $explicitExpiration
        );
    }

    /**
     * @throws \Throwable
     */
    public function getLatestSeries(string $contest, int $year): int
    {
        return $this->cache->load(
            sprintf("lastSeries_%s", $contest),
            function (&$dependencies) use ($contest, $year) {
                $dependencies[Cache::EXPIRE] = $this->expiration;
                $json = $this->downloader->download(new SeriesRequest($contest, $year));

                $series = end($json);
                return $series['series'];
            }
        );
    }

    private function getMedia(string $contest, int $year, string $path): ?string
    {
        $path = sprintf('%s%s/%d/media/%s', $this->problemManagerURL, $contest, $year, $path);
        $result = @file_get_contents($path);
        if ($result === false) {
            return null;
        }
        return $path;
    }

    public function getSolution(ProblemModel $problem, string $lang): ?string
    {
        $path = 'solution' . $problem->series . '-' . $problem->number . '.' . $lang . '.pdf';
        return $this->getMedia($problem->contest, $problem->year, $path);
    }

    public function getBatch(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serie' . $series->series . '.pdf';
        return $this->getMedia($contest, $series->year, $path);
    }

    public function getSerial(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serial' . $series->series . '.' . $lang . '.pdf';
        return $this->getMedia($contest, $series->year, $path);
    }
}
