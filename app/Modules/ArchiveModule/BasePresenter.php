<?php

namespace App\Modules\ArchiveModule;

use App\Components\TeamList\TeamListComponent;
use App\Components\TeamResults\TeamResultsComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter {

    /**
     * @persistent
     */
    public ?string $eventYear = null;

    private ModelEvent $event;
    protected ServiceEventList $serviceEvent;

    public function injectServiceEvent(ServiceEventList $serviceEvent): void {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @return ModelEvent
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function getEvent(): ModelEvent {
        if (!isset($this->event)) {
            if (isset($this->eventYear)) {
                if (is_numeric($this->eventYear)) {
                    $year = $this->eventYear;
                    $month = null;
                } else {
                    [$year, $month] = explode('-', $this->eventYear);
                }
                $events = $this->serviceEvent->getEventsByYear([9], $year);
                if (count($events)) {
                    $event = isset($month) ? reset($events) : end($events);
                }
            }
            if (!isset($event)) {
                $event = $this->serviceEvent->getNewest([9]);
            }

            if (!isset($event)) {
                throw new BadRequestException(_('Event not found'), IResponse::S404_NOT_FOUND);
            }
            $this->event = $event;
        }
        return $this->event;
    }

    protected function createComponentTeamList(): TeamListComponent {
        return new TeamListComponent($this->getContext(), $this->event->eventId);
    }

    protected function createComponentTeamResults(): TeamResultsComponent {
        return new TeamResultsComponent($this->getContext(), $this->event->eventId);
    }

    protected function changeViewYear(): void {
        parent::changeViewByLang();
    }

    protected function getNavItems(): array {
        return [];
    }
}
