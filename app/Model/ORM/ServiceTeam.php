<?php

namespace App\Model\ORM;

use DOMDocument;
use Exception;
use Fykosak\FKSDBDownloader\Downloader\AbstractSOAPService;

class ServiceTeam extends AbstractSOAPService {

    private array $teams = [];

    /**
     * @param int $eventId
     * @return ModelTeam[]
     * @throws Exception
     */
    public function getTeams(int $eventId): array {
        if (!isset($this->teams[$eventId])) {
            $this->teams[$eventId] = [];
            $xml = $this->downloader->createTeamList($eventId);
            $doc = new DOMDocument();
            $doc->loadXML($xml);
            foreach ($doc->getElementsByTagName('team') as $teamNode) {
                $this->teams[$eventId][] = ModelTeam::createFromXMLNode($teamNode);
            }
        }
        return $this->teams[$eventId];
    }
}
