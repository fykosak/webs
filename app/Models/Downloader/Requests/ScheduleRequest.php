<?php

declare(strict_types=1);

namespace App\Models\Downloader\Requests;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final readonly class ScheduleRequest implements Request
{
    /**
     * @param string[] $types
     */
    public function __construct(
        private int $eventId,
        private array $types,
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
