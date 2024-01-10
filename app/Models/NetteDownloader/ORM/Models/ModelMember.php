<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

/**
 * Member is a person attendina a team event.
 */
class ModelMember extends ModelPerson
{
    public ?array $school;
}
