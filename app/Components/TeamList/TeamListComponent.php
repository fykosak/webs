<?php

declare(strict_types=1);

namespace App\Components\TeamList;

use App\Components\Flags\FlagsComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class TeamListComponent extends BaseComponent
{

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;

    protected string $category;
    protected array $teams;

    public function __construct(Container $container, int $eventId)
    {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    protected function createComponentFlags(): FlagsComponent
    {
        return new FlagsComponent($this->getContext());
    }

    /**
     * @throws \Throwable
     */
    public function loadTeams(): void
    {
        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            $category = $team->category;
            if (!isset($teams[$category])) {
                $teams[$category] = [];
            }
            $teams[$category][] = $team;
        }
        $this->teams = $teams;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->loadTeams();

        $this->template->teams = $this->teams;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamList.latte');
    }
}
