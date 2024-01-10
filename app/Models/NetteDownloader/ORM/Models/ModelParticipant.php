<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

class ModelParticipant extends ModelPerson
{
    public ?int $schoolId = null;
    public ?string $schoolName = null;
    public ?string $countryIso = null;
}
