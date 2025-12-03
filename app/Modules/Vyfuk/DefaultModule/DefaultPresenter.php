<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\Models\EventModel;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
use App\Models\Downloader\Services\ProblemService;
use App\Models\Downloader\Services\EventService;
use App\Models\Downloader\Services\FileService;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;
use InvalidArgumentException;

class DefaultPresenter extends BasePresenter
{
    private readonly ProblemService $problemService;
    private readonly FileService $fileService;
    private EventService $eventService;

    public function injectService(
        ProblemService $problemService,
        FileService $fileService,
        EventService $eventService
    ): void {
        $this->problemService = $problemService;
        $this->fileService = $fileService;
        $this->eventService = $eventService;
    }

    public function renderDefault(): void
    {
        $this->template->newsList = $this->loadNews();

        //$year = $this->getCurrentYear()->year;
        //$series = $this->problemService->getLatestSeries('vyfuk', $year);
        //$seriesModel = $this->problemService->getSeries('vyfuk', $year, $series);

        $seriesId = $this->problemService->getLatestSeriesId(ProblemService::VYFUK);
        $series = $this->problemService->getSeries($seriesId);

        $previousSeriesId = $this->getPreviousSeriesId($series->contestYear['year'], $series->seriesId);
        $previousSeries = $this->problemService->getSeries($previousSeriesId);

        $this->template->series = $series;

        $this->template->previousSeries = $previousSeries;
        $this->template->solutionsReady = $this->solutionsReady($previousSeries, $this->lang);
        $this->template->resultsReady = $this->resultsReady($previousSeries);
        $this->template->nearestEvent = $this->getNearestEvent();
    }

    private function getPreviousSeriesId(int $year, int $currentSeriesId): int
    {
        $currentContestYear = $this->problemService->getYear(ProblemService::VYFUK, $year);
        $series = $currentContestYear->series;

        // assumes that series contains only released series ordered by deadline
        if (count($series) <= 1) {
            $previousContestYear = $this->problemService->getYear(ProblemService::VYFUK, $year - 1);
            return end($previousContestYear->series)->seriesId;
        }

        for ($i = 1; $i < count($series); $i++) {
            if ($series[$i]->seriesId === $currentSeriesId) {
                return $series[$i - 1]->seriesId;
            }
        }

        throw new InvalidArgumentException('Expected to find previous series');
    }


    public function solutionsReady(SeriesModel $series, $lang): bool
    {
        foreach ($series->problems as $problem) {
            if ($this->fileService->getSolution('vyfuk', $series, $problem, $lang) !== null) {
                return true;
            }
        }

        return false;
    }

    public function resultsReady(SeriesModel $series): bool
    {
        $results = $this->downloader->download(new SeriesResultsRequest($this->getContestId(), $series->contestYear['year']));

        if (isset($results['tasks']['VYFUK_6'][$series->label])) {
            return true;
        } else {
            return false;
        }
    }

    public function loadNews(): array
    {
        $json = file_get_contents(__DIR__ . '/templates/Default/news.json');
        $newsList = json_decode($json, true);

        return $newsList;
    }

    public function getNearestEvent(): ?EventModel
    {
        $eventTypeIds = [10, 11, 12, 18];
        $nearestEventDeadlines = [];
        foreach ($eventTypeIds as $eventTypeId) {
            if ($this->eventService->getNewest([$eventTypeId])->begin > new \DateTime()) {
                $nearestEventDeadlines[$eventTypeId] = $this->eventService->getNewest([$eventTypeId])->begin;
            }
        }

        asort($nearestEventDeadlines);

        if (count($nearestEventDeadlines) === 0) {
            return null;
        }

        return $this->eventService->getNewest([array_keys($nearestEventDeadlines)[0]]);
    }
}
