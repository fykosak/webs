<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final class ScheduleRequest implements Request
{
    /**
     * @param string[] $types
     */
    public function __construct(
        private readonly int $eventId,
        private readonly array $types,
    ) {
    }

    public function getMethod(): string
    {
        return 'events/' . $this->eventId . '/schedule';
    }

    /**
     * @phpstan-return array{types:string[]}
     */
    public function getParams(): array
    {
        return [
            'types' => $this->types,
        ];
    }

    public function getCacheKey(): string
    {
        return sprintf('schedule.%d.%s', $this->eventId, join('-', $this->types));
    }
}
