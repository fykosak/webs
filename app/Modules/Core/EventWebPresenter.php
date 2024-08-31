<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\Downloader\DummyService;
use App\Models\Downloader\EventService;
use App\Models\Downloader\EventModel;
use Nette\Application\UI\Template;

abstract class EventWebPresenter extends BasePresenter
{
    protected readonly DummyService $dummyService;
    protected EventService $eventService;

    public function injectEventWebServices(
        DummyService $dummyService,
        EventService $eventService
    ): void {
        $this->dummyService = $dummyService;
        $this->eventService = $eventService;
    }

    /**
     * @throws \Throwable
     */
    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->newestEvent = $this->getNewestEvent();
        return $template;
    }

    /**
     * @throws \Throwable
     */
    protected function getNewestEvent(): EventModel
    {
        static $newestEvent;
        if (!isset($newestEvent)) {
            $newestEvent = $this->eventService->getNewest($this->getEventIds());
        }
        return $newestEvent;
    }

    /**
     * @return int[]
     */
    abstract protected function getEventIds(): array;
}
