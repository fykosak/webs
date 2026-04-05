<?php

declare(strict_types=1);

namespace App\Models\Downloader\Requests;

use Fykosak\FKSDBDownloaderCore\Requests\Request;

final class EventOrganizersRequest implements Request
{
    public function __construct(private readonly int $eventId)
    {
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
