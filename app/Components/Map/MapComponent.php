<?php

declare(strict_types=1);

namespace App\Components\Map;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelTeam;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class MapComponent extends DIComponent
{
    private static int $uniqueId = 0;

    private DummyService $dummyService;

    protected int $teamCount;
    /** @var string[] */
    protected array $teamCountries;

    public function __construct(Container $container, private readonly ModelEvent $event)
    {
        parent::__construct($container);
    }

    public function injectServiceTeam(DummyService $dummyService): void
    {
        $this->dummyService = $dummyService;
    }

    /**
     * @throws \Throwable
     */
    public function processTeams(): void
    {
        $this->teamCount = 0;
        $this->teamCountries = [];
        foreach ($this->dummyService->get(new TeamsRequest($this->event->eventId), ModelTeam::class) as $team) {
            if (!in_array($team->state, ['participated', 'disqualified', 'applied', 'pending', 'approved'])) {
                continue;
            }
            $this->teamCount++;
            foreach ($team->members as $member) {
                if (!isset($member->school['countryISO'])) {
                    continue;
                }
                if (
                    !in_array($member->school['countryISO'], $this->teamCountries) &&
                    strtolower($member->school['countryISO']) !== 'zz'
                ) {
                    $this->teamCountries[] = $member->school['countryISO'];
                }
            }
        }
    }

    /**
     * @throws \Throwable
     */
    public function render(bool $inverseColors = false): void
    {
        $this->processTeams();

        $this->template->teamCount = $this->teamCount;
        $this->template->teamCountries = $this->teamCountries;

        $this->template->uniqueId = self::$uniqueId++;
        $this->template->inverseColors = $inverseColors;

        $this->template->lang = $this->translator->lang;
        $this->template->event = $this->event;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'map.latte');
    }
}
