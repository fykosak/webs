<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Models\Downloader\Models\TeamModel;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;

class ReportsPresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        /*   $this->template->reports = $this->reportService->getTable()
               ->where('lang = ? AND event_id = ?', $this->lang, $this->getEvent()->eventId);*/
        $this->template->year = $this->getEvent()->begin->format('Y');
        $this->setPageTitle(new PageTitle($this->csen('Ohlasy účastníků', 'Contestants\' reports')));
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function getTeams(array $teamIds): array
    {
        return \array_filter(
            $this->dummyService->get(new TeamsRequest($this->getEvent()->eventId), TeamModel::class),
            fn(TeamModel $team): bool => in_array($team->teamId, $teamIds)
        );
    }
}
