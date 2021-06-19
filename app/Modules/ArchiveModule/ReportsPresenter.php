<?php

namespace App\Modules\ArchiveModule;

use App\Models\ORM\ReportService;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Nette\Application\BadRequestException;

class ReportsPresenter extends BasePresenter {

    private ReportService $reportService;

    public function injectReportService(ReportService $reportService): void {
        $this->reportService = $reportService;
    }

    public function renderDefault(): void {
        $this->template->reports = $this->reportService->getTable()->where('lang', $this->lang);
        $this->setPageTitle(_('Contestants\' reports'));
    }

    /**
     * @param array $teamIds
     * @return array
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function getTeams(array $teamIds): array {
        return \array_filter(
            $this->serviceEventDetail->getAll($this->getEvent()->eventId),
            fn(ModelTeam $team) => in_array($team->teamId, $teamIds));
    }
}
