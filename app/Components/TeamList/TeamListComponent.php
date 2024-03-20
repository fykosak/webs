<?php

declare(strict_types=1);

namespace App\Components\TeamList;

use App\Components\Flags\FlagsComponent;
use App\Models\NetteDownloader\ORM\Models\ModelTeam;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class TeamListComponent extends DIComponent
{
    protected DummyService $serviceTeam;
    protected int $eventId;

    protected string $category;
    protected array $teams;

    public function __construct(Container $container, int $eventId)
    {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(DummyService $serviceTeam): void
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
        foreach ($this->serviceTeam->get(new TeamsRequest($this->eventId), ModelTeam::class) as $team) {
            $category = $team->category;
            if (strlen($category) === 0) {
                continue;
            }
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
        $this->template->lang = $this->translator->lang;
        $this->template->teams = $this->teams;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamList.latte');
    }
}
