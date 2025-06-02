<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use DateTime;
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
     * Get the lastest upcoming series.
     *
     * Gets all series for contest year and filters the ones that are not after
     * deadline. If there is at least one, it returns the first one (with the
     * correct ordering in config this will be the series with the closest
     * upcoming deadline).
     *
     * If the is no active series, it returns the last series defined.
     *
     * @throws \Throwable
     */
    public function getLatestSeries(string $contest, int $year): int
    {
        return $this->cache->load(
            sprintf("lastSeries_%s", $contest),
            function (&$dependencies) use ($contest, $year) {
                $dependencies[Cache::Expire] = $this->expiration;
                $json = $this->downloader->download(new SeriesRequest($contest, $year));

                $futureSeries = array_filter($json, function ($value, $key) {
                    return $value['deadline'] && (new DateTime($value['deadline']) > new DateTime());
                }, ARRAY_FILTER_USE_BOTH);

                if ($futureSeries) {
                    $series = reset($futureSeries);
                    return $series['series'];
                }

                $series = end($json);
                return $series['series'];
            }
        );
    }

    /**
     * @throws \Throwable
     */
    public function getYearJson(string $contest, int $year): array
    {
        return $this->cache->load(
            sprintf("yearJson_%s_%d", $contest, $year),
            function (&$dependencies) use ($contest, $year) {
                $dependencies[Cache::Expire] = $this->expiration;
                return $this->downloader->download(new SeriesRequest($contest, $year));
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

    public function getYearbook(string $contest, int $year, string $lang): ?string
    {
        $path = 'yearbook.pdf';
        return $this->getMedia($contest, $year, $path);
    }
}
