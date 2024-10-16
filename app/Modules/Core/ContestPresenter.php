<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\Downloader\ContestModel;
use App\Models\Downloader\ContestRequest;
use App\Models\Downloader\ContestYearModel;
use App\Models\Downloader\DummyService;
use DateTime;

abstract class ContestPresenter extends BasePresenter
{
    private readonly DummyService $dummyService;
    public function injectDummyService(DummyService $dummyService): void
    {
        $this->dummyService = $dummyService;
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
    abstract public function getContestId(): int;
    abstract public function getContestName(): string;
}
