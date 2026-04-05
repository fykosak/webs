<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

final class EventOrganizerModel
{
    public int $organizerId;
    public ?string $note;
    public PersonModel $person;
}
