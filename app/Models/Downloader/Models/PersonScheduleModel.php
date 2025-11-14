<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

final class PersonScheduleModel
{
    public PersonModel $person;
    public int $scheduleItemId;
}
