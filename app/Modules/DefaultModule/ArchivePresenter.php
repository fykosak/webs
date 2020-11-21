<?php

namespace App\Modules\DefaultModule;

use App\Components\TeamListComponent;
use App\Components\TeamResultsComponent;
use App\Model\ORM\ModelEvent;
use App\Model\ORM\ServiceEvent;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class ArchivePresenter extends BasePresenter {
    /**
     * @var int
     * @persistent
     */
    public $year;

    protected ModelEvent $event;

    protected ServiceEvent $serviceEvent;

    public function injectServiceEvent(ServiceEvent $serviceEvent): void {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws BadRequestException
     */
    protected function startUp(): void {
        parent::startUp();
        $event = $this->serviceEvent->getEventByYear($this->year);
        if (is_null($event)) {
            throw new BadRequestException(_('Event not found'), IResponse::S404_NOT_FOUND);
        }
        $this->event = $event;
    }

    protected function createComponentTeamList(): TeamListComponent {
        return new TeamListComponent($this->getContext(), $this->event->eventId);
    }

    protected function createComponentTeamResults(): TeamResultsComponent {
        return new TeamResultsComponent($this->getContext(), $this->event->eventId);
    }
}
