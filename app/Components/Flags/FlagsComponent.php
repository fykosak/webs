<?php

namespace App\Components\Flags;

use \Fykosak\Utils\BaseComponent\BaseComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use \Fykosak\NetteFKSDBDownloader\ORM\Models\ModelParticipant;
use Nette\DI\Container;

class FlagsComponent extends BaseComponent
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }


    public function getFlagForParticipant(ModelParticipant $participant){
        return $participant->countryIso;
    }

    public function getFlagsForTeam(ModelTeam $team){
        $flags = [];
        foreach ($team->participants as $participant){
            $flags[] = $this->getFlagForParticipant($participant);
        }
        return $flags;
    }

    public function getUniqueFlagsForTeam(ModelTeam $team){
        return array_unique($this->getFlagsForTeam($team));
    }

    public function renderFlag(ModelParticipant $participant){
        $this->template->flags = [$this->getFlagForParticipant($participant)];

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }

    public function renderFlags(ModelTeam $team){
        $this->template->flags = $this->getFlagsForTeam($team);

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }

    public function renderUniqueFlags(ModelTeam $team){
        $this->template->flags = $this->getUniqueFlagsForTeam($team);

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }
}