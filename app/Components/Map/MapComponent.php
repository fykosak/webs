<?php

declare(strict_types=1);

namespace App\Components\Map;

use App\Models\GamePhaseCalculator;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelTeam;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class MapComponent extends BaseComponent
{
    private static int $uniqueId = 0;

    private DummyService $dummyService;
    protected int $forEventId;

    protected int $teamCount;
    /** @var string[] */
    protected array $teamCountries;

    protected GamePhaseCalculator $gamePhaseCalculator;

    public function __construct(Container $container, GamePhaseCalculator $calculator, ModelEvent $event)
    {
        parent::__construct($container);
        $this->forEventId = $event->eventId;
        $this->gamePhaseCalculator = $calculator;
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
        foreach ($this->dummyService->get(new TeamsRequest($this->forEventId), ModelTeam::class) as $team) {
            if (!in_array($team->status, ['participated', 'disqualified', 'applied', 'pending', 'approved'])) {
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
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'map.latte');
    }
}
