<?php

declare(strict_types=1);

namespace App\Components\ApiResults;

use App\Models\Downloader\FKSDBDownloader;
use App\Models\Game\Connector;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\Components\DIComponent;
use Nette\Application\AbortException;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Container;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Throwable;

class ApiResultsComponent extends DIComponent
{
    private readonly Connector $gameServerApiConnector;
    private readonly FKSDBDownloader $downloader;

    public function __construct(
        Container $container,
        private readonly int $eventId
    ) {
        parent::__construct($container);
    }

    public function inject(Connector $connector, FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
        $this->gameServerApiConnector = $connector;
    }

    /**
     * @throws Throwable
     */
    private function serialiseTeams(): array
    {
        $teams = [];
        foreach ($this->downloader->download(new TeamsRequest($this->eventId)) as $team) {
            if ($team['state'] === 'canceled') {
                continue;
            }

            $members = [];
            foreach ($team['members'] as $member) {
                $members[] = [
                    'name' => $member['name'],
                    'schoolName' => $member['school']['nameAbbrev'] ?? '',
                    'countryIso' => $member['school']['countryISO'] ?? '',
                ];
            }
            $teams[] = [
                'teamId' => $team['teamId'],
                'name' => $team['name'],
                'category' => $team['category'],
                'participants' => $members,
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
        if (!$data['times']['visible']) {
            // results are hidden
            $data['submits'] = null;
            foreach ($data['teams'] as &$team) {
                $team['bonus'] = null;
            }
        }
        return $data;
    }

    public function render(): void
    {
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    public function renderTeamsData(): void
    {
        echo Json::encode($this->serialiseTeams());
    }

    /**
     * @throws Throwable
     * @throws JsonException
     */
    public function renderResultsData(): void
    {
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
