<?php

declare(strict_types=1);

namespace App\Components\Flags;

use App\Models\Downloader\TeamMemberModel;
use App\Models\Downloader\TeamModel;
use Fykosak\Utils\Components\DIComponent;

class FlagsComponent extends DIComponent
{
    public function getFlagForMember(TeamMemberModel $member): string
    {
        return $member->school['countryIso'] ?? '';
    }

    /**
     * @phpstan-return string[]
     */
    public function getFlagsForTeam(TeamModel $team): array
    {
        $flags = [];
        foreach ($team->members as $member) {
            $flags[] = $this->getFlagForMember($member);
        }
        return $flags;
    }

    /**
     * @phpstan-return string[]
     */
    public function getUniqueFlagsForTeam(TeamModel $team): array
    {
        return array_unique($this->getFlagsForTeam($team));
    }

    public function renderFlag(TeamMemberModel $member): void
    {
        $this->template->render(
            __DIR__ . DIRECTORY_SEPARATOR . 'flags.latte',
            ['flags' => [$this->getFlagForMember($member)]]
        );
    }

    public function renderFlags(TeamModel $team): void
    {
        $this->template->render(
            __DIR__ . DIRECTORY_SEPARATOR . 'flags.latte',
            ['flags' => $this->getFlagsForTeam($team)]
        );
    }

    public function renderUniqueFlags(TeamModel $team): void
    {
        $this->template->render(
            __DIR__ . DIRECTORY_SEPARATOR . 'flags.latte',
            ['flags' => $this->getUniqueFlagsForTeam($team)]
        );
    }
}
