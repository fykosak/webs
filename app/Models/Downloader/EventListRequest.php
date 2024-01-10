<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class EventListRequest implements Request
{
    /** @var int[] */
    private array $eventTypes;

    /**
     * @param int[] $eventTypes
     */
    public function __construct(array $eventTypes)
    {
        $this->eventTypes = $eventTypes;
    }

    /**
     * @phpstan-return array{types:int[]}
     */
    public function getParams(): array
    {
        return [
            'eventTypes' => $this->eventTypes,
        ];
    }

    public function getCacheKey(): string
    {
        return sprintf('event-list.%s', join('-', $this->eventTypes));
    }

    public function getMethod(): string
    {
        return 'events/';
    }
}
