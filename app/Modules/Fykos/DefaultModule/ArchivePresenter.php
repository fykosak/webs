<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
use App\Models\Downloader\Services\FileService;
use App\Models\Downloader\Services\ProblemService;
use Fykosak\FKSDBDownloaderCore\DownloaderException;
use Nette\Application\BadRequestException;
use Nette\Caching\Cache;

class ArchivePresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = null;
    /** @persistent */
    public ?int $series = null;

    private readonly FileService $fileService;
    private readonly ProblemService $problemService;
    private string $expire = '30 minutes';

    public function injectServiceProblem(
        FileService $fileService,
        ProblemService $problemService
    ): void {
        $this->problemService = $problemService;
        $this->fileService = $fileService;
    }

    /**
     * @throws \Throwable
     */
    public function renderSerial(): void
    {
        $this->template->selectedYear = $this->year;
        $this->template->yearsAndSeries = $this->cache->load(
            $this->name . ':getYearPartSerialLinks:' . $this->lang,
            function (&$dependencies) {
                // TODO: maybe get global default? How?
                $dependencies[Cache::Expire] = $this->expire;
                return $this->getYearPartSerialLinks($this->lang);
            }
        );
    }

    private function getYearPartSerialLinks(string $lang): array
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
        $years = $this->problemService->getYears(problemService::FYKOS);
        $res = [];
        foreach ($years as $year) {
            $links = [];
            foreach ($year->series as $series) {
                /// TODO: `ProblemService` needs a refactor, this is bad!!!
                $series = $this->problemService->getSeries($series->seriesId);
                $link = $this->fileService->getSerial('fykos', $series, $lang);
                if ($link !== null) {
                    $links[$series->label] = $link;
                }
            }
            if (count($links) === 0) {
                continue;
            }
            $res[$year->year] = $links;
        }
        krsort($res);
        return $res;
    }

    /**
     * Shows the series list in case year or series is not specified in the URL.
     * With a specified route enables to have subroute with year and series without the need of a submodule.
     */
    public function actionProblems(): void
    {
        if (is_null($this->year) || is_null($this->series)) {
            $this->setView('series');
        }
    }

    public function renderProblems(int $year, int $series): void
    {
        try {
            $seriesList = $this->fileService->getArchiveSeriesList('fykos', $year);
        } catch (DownloaderException) {
            throw new BadRequestException('Year does not exist', 404);
        }
        if (!array_key_exists($series, $seriesList)) {
            throw new BadRequestException('Series does not exist', 404);
        }

        $seriesModel = $seriesList[$series];

        $problems = [];
        foreach ($seriesModel->problems as $problemNum) {
            $problems[$problemNum] = $this->fileService->getArchiveProblem('fykos', $year, $series, $problemNum);
        }

        $this->template->series = $seriesModel;
        $this->template->problems = $problems;
    }

    public function renderSeries(): void
    {
        $years = [];

        for ($i = 39; $i > 0; $i--) {
            try {
                $years[$i] = $this->fileService->getArchiveSeriesList('fykos', $i);
            } catch (DownloaderException $e) {
            }
        }
        $this->template->years = $years;
    }

    protected function createComponentProblem(): ProblemComponent
    {
        return new ProblemComponent(
            $this->getContext(),
            $this->fileService->getArchiveSeriesList('fykos', $this->year)[$this->series]
        );
    }

    protected function createComponentImagePreviewModal(): ImagePreviewModalComponent
    {
        return new ImagePreviewModalComponent($this->getContext());
    }
}
