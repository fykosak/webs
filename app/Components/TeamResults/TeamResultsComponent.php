<?php

namespace App\Components\TeamResults;

use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class TeamResultsComponent extends BaseComponent {

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;

    public function __construct(Container $container, int $eventId) {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void {
        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            $category = $team->category;
            if (!isset($teams[$category])) {
                $teams[$category] = [];
            }
            $teams[$category][] = $team;
        }
        $this->template->teams = $teams;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
    }
}
