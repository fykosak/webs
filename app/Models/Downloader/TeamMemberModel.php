<?php

declare(strict_types=1);

namespace App\Models\Downloader;

/**
 * Member is a person attendina a team event.
 */
class TeamMemberModel extends PersonModel
{
    public ?array $school;
}
