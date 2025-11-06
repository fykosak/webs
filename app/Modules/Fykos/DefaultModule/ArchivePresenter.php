<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\ProblemService;
use Nette\Caching\Cache;

class ArchivePresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = null;

    private readonly ProblemService $problemService;
    private string $expire = '30 minutes';

    public function injectServiceProblem(ProblemService $problemService): void
    {
        $this->problemService = $problemService;
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
        $contest = $this->getContest();
        $res = [];
        foreach ($contest->years as $year) {
            try {
                $info = $this->problemService->getYearJson($contest->contest, $year->year);
                $links = [];
                foreach (array_keys($info) as $part) {
                    /// TODO: `ProblemService` needs a refactor, this is bad!!!
                    $series = $this->problemService->getSeries(
                        $contest->contest,
                        $year->year,
                        $part
                    );
                    $link = $this->problemService->getSerial(
                        $contest->contest,
                        $series,
                        $lang
                    );
                    if ($link !== null) {
                        $links[$part] = $link;
                    }
                }
                if (count($links) === 0) {
                    continue;
                }
                $res[$year->year] = $links;
            } catch (\Throwable $th) {
                // may not have year info
            }
        }
        krsort($res);
        return $res;
    }
}
