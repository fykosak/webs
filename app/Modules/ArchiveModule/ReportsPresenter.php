<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

use App\Models\ORM\ReportService;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Nette\Application\BadRequestException;

class ReportsPresenter extends BasePresenter
{

    private ReportService $reportService;

    public function injectReportService(ReportService $reportService): void
    {
        $this->reportService = $reportService;
    }

    /**
     * @return void
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $this->template->reports = $this->reportService->getTable()
            ->where('lang = ? AND event_id = ?', $this->lang, $this->getEvent()->eventId);
        $this->setPageTitle(_('Contestants\' reports'));
    }

    /**
     * @param array $teamIds
     * @return array
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function getTeams(array $teamIds): array
    {
        return \array_filter(
            $this->serviceEventDetail->getTeams($this->getEvent()->eventId),
            fn(ModelTeam $team): bool => in_array($team->teamId, $teamIds)
        );
    }
}
