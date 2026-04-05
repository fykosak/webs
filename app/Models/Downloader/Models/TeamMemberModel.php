<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

/**
 * Member is a person attending a team event.
 */
class TeamMemberModel extends PersonModel
{
    public ?array $school;
}
