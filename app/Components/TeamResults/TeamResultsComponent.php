<?php

namespace App\Components;

use App\Model\ORM\ServiceTeam;
use Exception;
use Nette\DI\Container;

class TeamResultsComponent extends BaseComponent {

    protected ServiceTeam $serviceTeam;
    protected int $eventId;

    public function __construct(Container $container, int $eventId) {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(ServiceTeam $serviceTeam): void {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @throws Exception
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
        $this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
        parent::render();
    }
}
