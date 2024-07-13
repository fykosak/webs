<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

class EventOrganizersRequest implements Request
{
    private int $eventId;

    public function __construct(int $eventId)
    {
        $this->eventId = $eventId;
    }

    public function getMethod(): string
    {
        return 'events/' . $this->eventId . '/organizers';
    }

    public function getParams(): array
    {
        return [
            'eventId' => $this->eventId,
        ];
    }

    public function getCacheKey(): string
    {
        return sprintf('event-organizers-list.%d', $this->eventId);
    }
}
