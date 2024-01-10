<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class OrganizersRequest implements Request
{
    private int $contestId;
    private ?int $year;

    public function __construct(int $contestId, ?int $year = null)
    {
        $this->contestId = $contestId;
        $this->year = $year;
    }

    public function getMethod(): string
    {
        return 'contests/' . $this->contestId . '/organizers';
    }

    /**
     * @phpstan-return array{year?:int}
     */
    public function getParams(): array
    {
        return isset($this->year) ? ['year' => $this->year] : [];
    }

    public function getCacheKey(): string
    {
        return sprintf('organizers.%s-%s', $this->contestId, $this->year ?? 'all');
    }
}
