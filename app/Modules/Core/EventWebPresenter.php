<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use App\Models\NetteDownloader\ORM\Services\ServiceEventList;
use Nette\Application\UI\Template;

abstract class EventWebPresenter extends BasePresenter
{
    protected readonly DummyService $dummyService;
    protected readonly ServiceEventList $serviceEventList;

    public function injectEventWebServices(
        DummyService $dummyService,
        ServiceEventList $serviceEventList
    ): void {
        $this->dummyService = $dummyService;
        $this->serviceEventList = $serviceEventList;
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
    protected function getNewestEvent(): ModelEvent
    {
        static $newestEvent;
        if (!isset($newestEvent)) {
            $newestEvent = $this->serviceEventList->getNewest($this->getEventIds());
        }
        return $newestEvent;
    }

    /**
     * @return int[]
     */
    abstract protected function getEventIds(): array;
}
