<?php

namespace App\Components\TeamList;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Exception;
use Nette;
use Nette\DI\Container;
use Throwable;
use App\Components\Flags\FlagsComponent;

class TeamListComponent extends BaseComponent {

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;

    protected string $category;
    protected array $teams;

    public function __construct(Container $container, int $eventId) {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void {
        $this->serviceTeam = $serviceTeam;
    }

    protected function createComponentFlags(): FlagsComponent
    {
        return new FlagsComponent($this->getContext());
    }

    public function loadTeams(){
        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            //Debugger::barDump($team);
            $category = $team->category;
            if (!isset($teams[$category])) {
                $teams[$category] = [];
            }
            $teams[$category][] = $team;
        }
        $this->teams = $teams;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function render(): void {
        $this->loadTeams();

        $this->template->teams = $this->teams;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamList.latte');
    }
}
