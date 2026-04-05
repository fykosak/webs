<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\Services\FileService;
use App\Models\Downloader\Services\ProblemService;
use Throwable;

class ArchivePresenter extends BasePresenter
{
    private readonly ProblemService $problemService;
    private readonly FileService $fileService;

    public function injectServiceProblem(
        FileService $fileService,
        ProblemService $problemService
    ): void {
        $this->fileService = $fileService;
        $this->problemService = $problemService;
    }

    /**
     * @throws \Throwable
     */
    public function renderSerial(): void
    {
        $this->template->problemService = $this->problemService;
        $this->template->fileService = $this->fileService;
        $this->template->contestYears = array_reverse($this->problemService->getYears(ProblemService::VYFUK));
        $this->template->hasAtLeastOneSerial = $this->checkYearsSerials();
        $this->template->serialTopics = $this->loadTopics();
    }

    private function checkYearsSerials(): array
    {
        $contestYears = $this->problemService->getYears(ProblemService::VYFUK);
        $hasAtLeastOneSerial = [];
        foreach ($contestYears as $contestYear) {
            $hasAtLeastOneSerial[$contestYear->year] = false;
            foreach ($contestYear->series as $series) {
                $series = $this->problemService->getSeries($series->seriesId);
                $serialPath = $this->fileService->getSerial('vyfuk', $series, $this->lang);
                if ($serialPath) {
                    $hasAtLeastOneSerial[$contestYear->year] = true;
                    break;
                }
            }
        }

        return $hasAtLeastOneSerial;
    }

    public function loadTopics(): array
    {
        $json = file_get_contents(__DIR__ . '/templates/Archive/topics.json');
        $serialTopics = json_decode($json, true);

        return $serialTopics;
    }
}
