<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\NetteDownloader\ORM\Services\AbstractJSONService;
use Nette\Caching\Storage;

final class ProblemService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, ProblemManagerDownloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

    public function getProblem(
        string $contest,
        int $year,
        int $series,
        int $number,
        ?string $explicitExpiration = null
    ): ProblemModel {
        return $this->getItem(
            new ProblemRequest($contest, $year, $series, $number),
            [],
            ProblemModel::class,
            false,
            $explicitExpiration
        );
    }
}
