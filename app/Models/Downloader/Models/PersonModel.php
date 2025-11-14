<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

class PersonModel
{
    public ?int $personId;

    public string $name;
    public ?string $email;
}
