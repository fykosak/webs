<?php

declare(strict_types=1);

namespace App\Models\Problems;

use Fykosak\NetteFKSDBDownloader\ORM\Services\AbstractJSONService;

final class ProblemService extends AbstractJSONService
{
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
