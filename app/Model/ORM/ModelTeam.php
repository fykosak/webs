<?php

namespace App\Model\ORM;

use DOMNode;
use Exception;

class ModelTeam extends AbstractSOAPModel {
    public int $teamId;
    public string $name;
    public string $status;
    public string $category;
    public \DateTimeInterface $created;
    public ?string $phone = null;
    public ?string $password = null;
    public ?int $points = null;
    public ?int $rankCategory = null;
    public ?int $rankTotal = null;
    public bool $forceA;
    public ?string $gameLang = null;
    public array $participants = [];

    /**
     * @param DOMNode $node
     * @return static
     * @throws Exception
     */
    public static function createFromXMLNode(DOMNode $node): self {
        $model = parent::createFromXMLNode($node);
        usort($model->participants, function (ModelParticipant $a, ModelParticipant $b) {
            return $a->schoolId <=> $b->schoolId;
        });
        return $model;
    }

    /**
     * @param DOMNode $node
     * @param static|AbstractSOAPModel $model
     * @return bool
     * @throws Exception
     */
    protected static function handleAccessProperty(\DOMNode $node, AbstractSOAPModel $model): bool {
        switch ($node->nodeName) {
            case 'participant':
                $model->participants[] = ModelParticipant::createFromXMLNode($node);
                return true;
        }
        return false;
    }
}