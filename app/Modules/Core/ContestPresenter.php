<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\Downloader\ContestModel;
use App\Models\Downloader\ContestRequest;
use App\Models\Downloader\ContestYearModel;
use App\Models\Downloader\DummyService;
use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Utils\DateTime;

abstract class ContestPresenter extends BasePresenter
{
    private readonly DummyService $dummyService;
    protected readonly FKSDBDownloader $downloader;
    protected readonly Cache $cache;

    final public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }


    public function injectDummyService(DummyService $dummyService): void
    {
        $this->dummyService = $dummyService;
    }
    public function injectCache(Storage $storage): void
    {
        $this->cache = new Cache($storage);
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->currentYear = $this->getCurrentYear();
    }

    public function getContest(): ContestModel
    {
        return $this->dummyService->getFlat(new ContestRequest($this->getContestId()), ContestModel::class);
    }

    public function getCurrentYear(): ?ContestYearModel
    {
        $contest = $this->getContest();
        foreach ($contest->years as $year) {
            if ($year->begin < new DateTime() && $year->end > new DateTime()) {
                return $year;
            }
        }
        return null;
    }

    public function getPointsYear(): ?ContestYearModel
    {
        return $this->cache->load("pointsYear", function () {
            foreach (array_reverse($this->getContest()->years) as $year) {
                try {
                    $results = $this->downloader->download(new SeriesResultsRequest($this->getContestId(), $year->year));
                    foreach ($results["submits"] as $y) {
                        foreach ($y as $c) {
                            foreach ($c["submits"] as $s) {
                                if ($s !== null) {
                                    return $year;
                                }
                            }
                        }
                    }
                } finally {
                }
            }
            return null;
        }, [
            Cache::Expire => '1 day',
        ]);
    }

    abstract public function getContestId(): int;
}
