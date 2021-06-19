<?php

namespace App\Modules\ArchiveModule;

use App\Models\ORM\ReportService;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;

class ReportsPresenter extends BasePresenter {

    private ReportService $reportService;

    public function __construct(ReportService $reportService) {
        $this->reportService = $reportService;
    }

    public function renderDefault(): void {
        $this->template->reports = $this->reportService->getTable()->where('lang', $this->lang);
        $this->setPageTitle(_("Contestants' reports"));
    }

    public function getTeams(array $teamIds): array {
        return \array_filter(
            $this->serviceEventDetail->getTeams($this->getEvent()->eventId),
            fn(ModelTeam $team) => in_array($team->teamId, $teamIds));
    }
}
