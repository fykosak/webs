<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\Downloader\ModelPerson;

class EventOrganizerModel
{
    public int $organizerId;
    public ?string $note;
    public ModelPerson $person;
}
