<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Nette\Utils\DateTime;

final class ContestYearModel
{
    public int $year;
    public bool $active;
    public DateTime $begin;
    public DateTime $end;
}
