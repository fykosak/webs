<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Models\NetteDownloader\ORM\Models\ModelTeam;
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
        $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'reports.json');
        $query = json_decode($data);
        $this->template->reports = array_filter($query, function ($item) {
            return $item->lang === $this->language->value && $item->event_id === $this->getEvent()->eventId;
        });
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
            $this->dummyService->get(new TeamsRequest($this->getEvent()->eventId), ModelTeam::class),
            fn(ModelTeam $team): bool => in_array($team->teamId, $teamIds)
        );
    }
}
