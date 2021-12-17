<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Models\ORM\ReportService;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;

class ReportsPresenter extends BasePresenter
{

    private ReportService $reportService;

    public function injectReportService(ReportService $reportService): void
    {
        $this->reportService = $reportService;
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $this->template->reports = $this->reportService->getTable()
            ->where('lang = ? AND event_id = ?', $this->lang, $this->getEvent()->eventId);
        $this->template->year = $this->getEvent()->begin->format('Y');
        $this->setPageTitle(new PageTitle(_('Contestants\' reports')));
    }

    /**
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
