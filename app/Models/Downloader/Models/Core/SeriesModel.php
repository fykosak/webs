<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\Core;

use DateTime;

// Could be an interface, but is defined as abstract class to be consistent with problem model
abstract class SeriesModel
{
    abstract public function getDeadline(): ?DateTime;
    abstract public function getLabel(): string;
    abstract public function getYear(): int;
}
