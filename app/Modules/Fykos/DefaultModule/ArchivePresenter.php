<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\Services\FileService;
use App\Models\Downloader\Services\ProblemService;
use Nette\Caching\Cache;

class ArchivePresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = null;

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
            ($this->name ?? 'ArchivePresenter') . ':getYearPartSerialLinks:' . $this->lang,
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
}
