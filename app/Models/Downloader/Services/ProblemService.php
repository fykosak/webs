<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use App\Models\Downloader\Downloaders\ProblemManagerDownloader;
use App\Models\Downloader\Models\ProblemManager\ContestYearModel;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
use App\Models\Downloader\Requests\ProblemManager\ContestYearRequest;
use App\Models\Downloader\Requests\ProblemManager\SeriesRequest;
use DateTime;
use Nette\Application\BadRequestException;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class ProblemService extends AbstractJSONService
{
    public const FYKOS = 1;
    public const VYFUK = 4;

    public function __construct(
        string $expiration,
        Storage $storage,
        ProblemManagerDownloader $downloader
    ) {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

    /**
     * @throws \Throwable
     */
    public function getSeries(
        int $seriesId,
    ): SeriesModel {
        return $this->getItem(
            new SeriesRequest($seriesId),
            [],
            SeriesModel::class,
            false
        );
    }

    /**
     * @phpstan-return ContestYearModel[]
     */
    public function getYears(int $contestId): array
    {
        return $this->getItem(
            new ContestYearRequest($contestId),
            [],
            ContestYearModel::class,
            true
        );
    }

    public function getYear(int $contestId, int $year): ?ContestYearModel
    {
        $contestYears = $this->getYears($contestId);
        foreach ($contestYears as $contestYear) {
            if ($contestYear->year === $year) {
                return $contestYear;
            }
        }

        return null;
    }

    public function getSeriesId(int $contestId, int $year, string $seriesLabel): int
    {
        $contestYear = $this->getYear($contestId, $year);
        foreach ($contestYear->series as $series) {
            if ($series->label === $seriesLabel) {
                return $series->seriesId;
            }
        }

        throw new BadRequestException('Series does not exist');
    }

    /**
     * Get the lastest upcoming series.
     *
     * Gets all series for contest and filters the ones that are not after
     * deadline. If there is at least one, it returns the first one (with the
     * correct ordering in config this will be the series with the closest
     * upcoming deadline).
     *
     * If the is no active series, it returns the last series defined.
     *
     * @throws \Throwable
     */
    public function getLatestSeriesId(int $contestId): int
    {
        return $this->cache->load(
            sprintf("lastSeries_%d", $contestId),
            function (&$dependencies) use ($contestId) {
                $dependencies[Cache::Expire] = $this->expiration;
                $years = $this->getYears($contestId);

                $series = [];
                foreach ($years as $year) {
                    $series = [
                        ...$series,
                        ...$year->series
                    ];
                }

                $futureSeries = array_filter(
                    $series,
                    function ($value, $key) {
                        return $value->deadline && (new DateTime($value->deadline) > new DateTime()) &&
                            (!$value->release || (new DateTime($value->release) < new DateTime()));
                    },
                    ARRAY_FILTER_USE_BOTH
                );

                usort($futureSeries, function ($a, $b) {
                    return (new DateTime($a->deadline)) <=> (new DateTime($b->deadline));
                });

                if ($futureSeries) {
                    $series = reset($futureSeries);
                    return $series->seriesId;
                }

                $series = end($series);
                return $series->seriesId;
            }
        );
    }
}
