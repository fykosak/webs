<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

use App\Components\Navigation\NavItem;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    /** @persistent */
    public ?string $eventYear = null;

    private ModelEvent $event;
    protected ServiceEventList $serviceEvent;

    public function injectServiceEvent(ServiceEventList $serviceEvent): void
    {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @return ModelEvent
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function getEvent(): ModelEvent
    {
        if (!isset($this->event)) {
            if (isset($this->eventYear)) {
                if (is_numeric($this->eventYear)) {
                    $year = $this->eventYear;
                    $month = null;
                } else {
                    [$year, $month] = explode('-', $this->eventYear);
                }
                $events = $this->serviceEvent->getEventsByYear([9], +$year);
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

    protected function getNavItems(): array
    {
        return [
            new NavItem(
                ':Archive:Default:default',
                [],
                _('Archive Home'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ),
            new NavItem(
                ':Archive:Teams:default',
                [],
                _('Týmy'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ),
            new NavItem(
                ':Archive:Results:default',
                [],
                _('Výsledky'),
                'visible-sm-inline glyphicon glyphicon-compressed'
            ),
            new NavItem(
                ':Archive:DetailedResults:default',
                [],
                _('Podrobné výsledky'),
                'visible-sm-inline glyphicon glyphicon-compressed'
            ),
            new NavItem(
                ':Archive:Reports:default',
                [],
                _('Reporty'),
                'visible-sm-inline glyphicon glyphicon-exclamation-sign'
            ),
        ];
    }

    /**
     * @return array
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function formatTemplateFiles(): array
    {
        $files = parent::formatTemplateFiles();
        $key = parent::createEventKey($this->getEvent());
        return [
            str_replace('/templates/', '/templates/' . $key . '/', $files[0]),
            str_replace('/templates/', '/templates/' . $key . '/', $files[1]),
            ...$files,
        ];
    }
}
