<?php

declare(strict_types=1);

namespace App\Components\ApiResults;

use App\Models\Game\Connector;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelParticipant;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Application\AbortException;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Container;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Throwable;

class ApiResultsComponent extends BaseComponent
{
    private Connector $gameServerApiConnector;
    private ServiceEventDetail $serviceTeam;
    private int $eventId;

    public function __construct(Container $container, int $eventId)
    {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectGameServerApiConnector(Connector $connector) {
        $this->gameServerApiConnector = $connector;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    private function serialiseTeams(): array
    {
        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            if ($team->status === 'cancelled') {
                continue;
            }

            $participants = [];
            /** @var ModelParticipant $participant */
            foreach ($team->participants as $participant) {
                $participants[] = [
                    'name' => $participant->name,
                    'schoolName' => $participant->schoolName,
                    'countryIso' => $participant->countryIso,
                ];
            }
            $teams[] = [
                'teamId' => $team->teamId,
                'name' => $team->name,
                'category' => $team->category,
                'participants' => $participants,
            ];
        }
        return $teams;
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function serialiseResults(): array
    {
        $data = $this->gameServerApiConnector->getResults();
        if (strtotime($data['times']['gameEnd']) <= time()) {
            $data['times']['visible'] = true;
        }
        if (!$data['times']['visible']) {
            // results are hidden
            $data["submits"] = null;
            foreach ($data["teams"] as &$team) {
                $team["bonus"] = null;
            }
        }
        return $data;
    }

    public function render() {

    }

    /**
     * @throws JsonException
     */
    public function renderTeamsData() {
        echo Json::encode($this->serialiseTeams());
    }

    /**
     * @throws Throwable
     * @throws JsonException
     */
    public function renderResultsData() {
        echo Json::encode($this->serialiseResults());
    }

    /**
     * @throws AbortException
     * @throws Throwable
     */
    public function handleResults(): void
    {
        $this->getPresenter()->sendResponse(new JsonResponse($this->serialiseResults()));
    }

}
