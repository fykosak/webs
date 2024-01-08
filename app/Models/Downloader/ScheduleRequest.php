<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class ScheduleRequest implements Request
{
    private int $eventId;
    /** @var string[] */
    private array $types;

    /**
     * @param string[] $types
     */
    public function __construct(int $eventId, array $types)
    {
        $this->types = $types;
        $this->eventId = $eventId;
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
