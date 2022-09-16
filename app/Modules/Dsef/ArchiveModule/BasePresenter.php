<?php

declare(strict_types=1);

namespace App\Modules\Dsef\ArchiveModule;

use App\Components\PersonSchedule\AllScheduleListComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Template;
use Nette\Http\IResponse;

abstract class BasePresenter extends \App\Modules\Dsef\Core\BasePresenter
{
    /** @persistent */
    public ?string $eventYear = null;

    /** @persistent */
    public ?string $eventMonth = null;

    protected ModelEvent $event;
    protected ServiceEventList $serviceEvent;

    public function injectServiceEvent(ServiceEventList $serviceEvent): void
    {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function getEvent(): ModelEvent
    {
        if (!isset($this->event)) {
            if (isset($this->eventYear) && isset($this->eventMonth)) {
                $year = $this->eventYear;
                $month = $this->eventMonth;
                $events = $this->serviceEvent->getEventsByYear(
                    [$this->context->getParameters()["eventTypeId"]],
                    +$year
                );
                $monthNumber =  array_search($month, BasePresenter::$months);
                $events = array_filter($events, function ($event) use ($monthNumber) {
                    return (int)$event->begin->format('n') - 1 === $monthNumber;
                });
                if (count($events) === 1) {
                    $event = end($events);
                }
            }

            if (!isset($event)) {
                throw new BadRequestException(_('Event not found'), IResponse::S404_NOT_FOUND);
            }
            $this->event = $event;
        }
        return $this->event;
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function formatTemplateFiles(): array
    {
        $files = parent::formatTemplateFiles();
        $key = parent::getEventKey($this->getEvent());

        return [
            str_replace('.latte', '.' . $key . '.' . $this->lang . '.latte', end($files)),
            str_replace('.latte', '.' . $key . '.latte', end($files)),
            ...$files,
        ];
    }

    /**
     * @throws \Throwable
     * @throws BadRequestException
     */
    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->event = $this->getEvent();
        $template->eventKey = parent::getEventKey($this->getEvent());
        return $template;
    }

    protected function createComponentScheduleParticipants(): AllScheduleListComponent
    {
        return new AllScheduleListComponent($this->event->eventId, $this->getContext());
    }
}
