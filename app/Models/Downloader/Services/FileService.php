<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use App\Models\Downloader\Downloaders\StaticDownloader;
use App\Models\Downloader\Models\Archive\ArchiveProblemModel;
use App\Models\Downloader\Models\Archive\ArchiveSeriesModel;
use App\Models\Downloader\Models\Core\ProblemModel;
use App\Models\Downloader\Models\Core\SeriesModel;
use App\Models\Downloader\Requests\Archive\ProblemRequest;
use App\Models\Downloader\Requests\Archive\SeriesRequest;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class FileService extends AbstractJSONService
{
    public function __construct(
        string $expiration,
        private string $staticURL,
        Storage $storage,
        StaticDownloader $downloader
    ) {
        parent::__construct($expiration, $storage);
        $this->downloader = $downloader;
    }

    private function getMedia(string $contest, int $year, string $path): ?string
    {
        $path = sprintf('%s%s/%d/media/%s', $this->staticURL, $contest, $year, $path);
        return $this->cache->load(
            $path,
            function (&$dependencies) use ($path) {
                $dependencies[Cache::Expire] = $this->expiration;
                $req = curl_init($path);
                curl_setopt($req, CURLOPT_NOBODY, true);
                $exists = curl_exec($req) && curl_getinfo($req, CURLINFO_HTTP_CODE) === 200;
                return $exists ? $path : null;
            }
        );
    }

    public function getSolution(string $contest, SeriesModel $series, ProblemModel $problem, string $lang): ?string
    {
        $path = 'solution' . $series->getLabel() . '-' . $problem->getOrder() . '.' . $lang . '.pdf';
        return $this->getMedia($contest, $series->getYear(), $path);
    }

    public function getBatch(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serie' . $series->getLabel() . '.pdf';
        return $this->getMedia($contest, $series->getYear(), $path);
    }

    public function getSerial(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serial' . $series->getLabel() . '.' . $lang . '.pdf';
        return $this->getMedia($contest, $series->getYear(), $path);
    }

    public function getYearbook(string $contest, int $year, string $lang): ?string
    {
        $path = 'yearbook.pdf';
        return $this->getMedia($contest, $year, $path);
    }

    public function getTasks(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'problems' . $series->getLabel() . '.pdf';
        return $this->getMedia($contest, $series->getYear(), $path);
    }

    public function getArchiveProblem(string $contest, int $year, int $series, int $number): ArchiveProblemModel
    {
        return $this->getItem(
            new ProblemRequest($contest, $year, $series, $number),
            [],
            ArchiveProblemModel::class,
            false,
            // When set to null, default $this->expiration is taken, so set it to a high value.
            // TODO invalidate on file change
            '1 year'
        );
    }

    /**
     * @return ArchiveSeriesModel[]
     */
    public function getArchiveSeriesList(string $contest, int $year): array
    {
        return $this->getItem(
            new SeriesRequest($contest, $year),
            [],
            ArchiveSeriesModel::class,
            true,
            '1 year'
        );
    }
}
