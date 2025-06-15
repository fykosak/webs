<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\ProblemService;
use Nette\Application\Attributes\Persistent;
use Nette\Caching\Cache;

class SerialArchivePresenter extends BasePresenter
{
    #[Persistent]
    public ?int $year = null;

    private readonly ProblemService $problemService;

    public function injectServiceProblem(ProblemService $problemService): void
    {
        $this->problemService = $problemService;
    }

    /**
     * @throws BadRequestException
     */
    public function renderDefault(): void
    {
        $expire = '30 minutes';
        $this->template->selectedYear = $this->year;
        $this->template->yearsAndSeries = $this->cache->load(
            "SerialArchive:yearsAndSeries",
            function (&$dependencies) use ($expire) {
                // TODO: maybe get global default? How?
                $dependencies[Cache::Expire] = $expire;
                return $this->getYearsAndSeries();
            }
        );
    }

    private function getSerialPart(string $contest, int $year, int $part): ?string
    {
        $series = $this->problemService->getSeries(
            $contest,
            $year,
            $part
        );
        return $this->problemService->getSerial($contest, $series, $this->lang);
    }

    private function getYearsAndSeries(): array
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
        $contest = $this->getContest();
        $res = [];
        foreach ($contest->years as $year) {
            try {
                $info = $this->problemService->getYearJson($contest->contest, $year->year);
                $links = [];
                foreach (array_keys($info) as $part) {
                    $link = $this->getSerialPart($contest->contest, $year->year, $part);
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
