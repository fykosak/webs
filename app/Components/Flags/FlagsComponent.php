<?php

declare(strict_types=1);

namespace App\Components\Flags;

use App\Models\NetteDownloader\ORM\Models\ModelMember;
use App\Models\NetteDownloader\ORM\Models\ModelTeam;
use Fykosak\Utils\Components\DIComponent;

class FlagsComponent extends DIComponent
{
    public function getFlagForMember(ModelMember $member): string
    {
        return $member->school['countryIso'] ?? '';
    }

    public function getFlagsForTeam(ModelTeam $team): array
    {
        $flags = [];
        foreach ($team->members as $member) {
            $flags[] = $this->getFlagForMember($member);
        }
        return $flags;
    }

    public function getUniqueFlagsForTeam(ModelTeam $team): array
    {
        return array_unique($this->getFlagsForTeam($team));
    }

    public function renderFlag(ModelMember $member): void
    {
        $this->template->flags = [$this->getFlagForMember($member)];
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }

    public function renderFlags(ModelTeam $team): void
    {
        $this->template->flags = $this->getFlagsForTeam($team);
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }

    public function renderUniqueFlags(ModelTeam $team): void
    {
        $this->template->flags = $this->getUniqueFlagsForTeam($team);
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'flags.latte');
    }
}
