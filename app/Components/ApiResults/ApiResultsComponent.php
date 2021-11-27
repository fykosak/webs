<?php

declare(strict_types=1);

namespace App\Components\ApiResults;

use App\Models\Game\Connector;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelParticipant;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\NetteFrontendComponent\Components\AjaxComponent;
use Nette\Application\AbortException;
use Nette\Application\UI\InvalidLinkException;
use Nette\DI\Container;

class ApiResultsComponent extends AjaxComponent
{
    private Connector $gameServerApiConnector;
    private ServiceEventDetail $serviceTeam;
    private int $eventId;
    private string $lang;

    public function __construct(Container $container, int $eventId, string $lang)
    {
        parent::__construct($container, 'api.results');
        $this->eventId = $eventId;
        $this->lang = $lang;
    }

    public function injectGameServerApiConnector(Connector $connector)
    {
        $this->gameServerApiConnector = $connector;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @return array
     * @throws \Throwable
     */
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
     * @throws \Throwable
     */
    public function getData(): array
    {
        $data = $this->gameServerApiConnector->getResults();
        if (strtotime($data['times']['gameEnd']) <= time()) {
            $data['times']['visible'] = true;
        }
        if (!$data['times']['visible']) {
            // results are hidden
            $data['submits'] = null;
            foreach ($data['teams'] as &$team) {
                $team['bonus'] = null;
            }
        }
        $data['lang'] = $this->lang;
        $data['teams'] = $this->serialiseTeams();
        return $data;
    }

    /**
     * @throws InvalidLinkException
     */
    protected function configure(): void
    {
        $this->addAction('results', 'results!');
    }

    /**
     * @throws AbortException
     * @throws \Throwable
     */
    public function handleResults(): void
    {
        $this->sendAjaxResponse();
    }
}
