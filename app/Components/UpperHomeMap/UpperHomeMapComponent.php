<?php

declare(strict_types=1);

namespace App\Components\UpperHomeMap;

use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelParticipant;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class UpperHomeMapComponent extends BaseComponent
{
    protected ServiceEventDetail $serviceTeam;
    protected GamePhaseCalculator $gamePhaseCalculator;

    protected int $teamCount;
    protected array $teamCountries;

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    public function __construct(Container $container, GamePhaseCalculator $calculator)
    {
        parent::__construct($container);
        $this->gamePhaseCalculator = $calculator;
    }

    /**
     * @throws \Throwable
     */
    public function processTeams(): void
    {
        $this->teamCount = 0;
        $this->teamCountries = [];

        foreach ($this->serviceTeam->getTeams($this->gamePhaseCalculator->getFKSDBEvent()->eventId) as $team) {
            $this->teamCount++;
            /* @var ModelParticipant $participant */
            foreach ($team->participants as $participant) {
                if (
                    !in_array($participant->countryIso, $this->teamCountries) &&
                    strtolower($participant->countryIso) !== 'zz'
                ) {
                    $this->teamCountries[] = $participant->countryIso;
                }
            }
        }
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->processTeams();

        $this->template->teamCount = $this->teamCount;
        $this->template->teamCountries = $this->teamCountries;

        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeMap.latte');
    }
}
