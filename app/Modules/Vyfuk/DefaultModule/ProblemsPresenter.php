<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
use App\Models\Downloader\Models\ProblemManager\PMSeriesModel;
use App\Models\Downloader\Services\FileService;
use App\Models\Downloader\Services\ProblemService;
use Nette\Application\Attributes\Persistent;
use InvalidArgumentException;

class ProblemsPresenter extends BasePresenter
{
    private readonly FileService $fileService;
    private readonly ProblemService $problemService;

    #[Persistent]
    public ?int $year = null;

    #[Persistent]
    public ?string $series = null;

    public function injectServiceProblem(FileService $fileService, ProblemService $problemService): void
    {
        $this->fileService = $fileService;
        $this->problemService = $problemService;
    }

    private function getSeries(): PMSeriesModel
    {
        $seriesId = $this->year && $this->series
            ? $this->problemService->getSeriesId(ProblemService::VYFUK, $this->year, $this->series)
            : $this->problemService->getLatestSeriesId(ProblemService::VYFUK);

        return $this->problemService->getSeries($seriesId);
    }

    private function getPreviousSeries(int $year, int $currentSeriesId): ?PMSeriesModel
    {
        $currentContestYear = $this->problemService->getYear(ProblemService::VYFUK, $year);
        $series = $currentContestYear->series;
        $currentSeries = $this->problemService->getSeries($currentSeriesId);

        // assumes that series contains only released series ordered by deadline
        if ($currentSeries->label === '1') {
            if ($year === 1) {
                return null;
            }
            $previousContestYear = $this->problemService->getYear(ProblemService::VYFUK, $year - 1);
            return $this->problemService->getSeries(end($previousContestYear->series)->seriesId);
        }

        for ($i = 1; $i < count($series); $i++) {
            if ($series[$i]->seriesId === $currentSeriesId) {
                return $this->problemService->getSeries($series[$i - 1]->seriesId);
            }
        }

        throw new InvalidArgumentException('Expected to find previous series');
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $series = $this->getSeries();
        $this->template->series = $series;
        $this->template->problems = $series->problems;

        $this->template->previousSeries = $this->getPreviousSeries($series->contestYear['year'], $series->seriesId);

        $this->template->currentContestYear = $this->problemService->getYear(
            ProblemService::VYFUK,
            $series->contestYear['year']
        );
        $this->template->fileService = $this->fileService;

        $yearsAndSeries = $this->problemService->getYears(ProblemService::VYFUK);
        usort($yearsAndSeries, function ($a, $b) {
            return $b->year <=> $a->year;
        });
        $this->template->yearsAndSeries = $yearsAndSeries;
    }

    protected function createComponentProblem(): ProblemComponent
    {
        return new ProblemComponent($this->getContext(), $this->getSeries());
    }

    protected function createComponentImagePreviewModal(): ImagePreviewModalComponent
    {
        return new ImagePreviewModalComponent($this->getContext());
    }
}
