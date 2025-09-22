<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use App\Models\Downloader\Downloaders\StaticDownloader;
use App\Models\Downloader\Models\ProblemManager\ProblemModel;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
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
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
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
                curl_close($req);
                return $exists ? $path : null;
            }
        );
    }

    public function getSolution(string $contest, SeriesModel $series, ProblemModel $problem, string $lang): ?string
    {
        $path = 'solution' . $series->label . '-' . $problem->seriesOrder . '.' . $lang . '.pdf';
        return $this->getMedia($contest, $series->contestYear['year'], $path);
    }

    public function getBatch(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serie' . $series->label . '.pdf';
        return $this->getMedia($contest, $series->contestYear['year'], $path);
    }

    public function getSerial(string $contest, SeriesModel $series, string $lang): ?string
    {
        $path = 'serial' . $series->label . '.' . $lang . '.pdf';
        return $this->getMedia($contest, $series->contestYear['year'], $path);
    }

    public function getYearbook(string $contest, int $year, string $lang): ?string
    {
        $path = 'yearbook.pdf';
        return $this->getMedia($contest, $year, $path);
    }
}
