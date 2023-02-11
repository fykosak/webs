<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Template;
use Nette\Http\IResponse;

abstract class BasePresenter extends \App\Modules\Fof\Core\BasePresenter
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
                $events = $this->serviceEvent->getEventsByYear(
                    [$this->context->getParameters()["eventTypeId"]],
                    +$year
                );
                if (count($events)) {
                    $event = isset($month) ? reset($events) : end($events);
                }
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
                new PageTitle(null, _('archive_home.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
                ':Default:Archive:',
            ),
            new NavItem(
                new PageTitle(null, _('teams.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
                ':Archive:Teams:default',
            ),
            new NavItem(
                new PageTitle(null, _('results.menu'), 'visible-sm-inline glyphicon glyphicon-compressed'), // TODO
                ':Archive:Results:default',
            ),
            //new NavItem(
            //    new PageTitle(null, _('detailed_results.menu'), 'visible-sm-inline glyphicon glyphicon-compressed'),
            //    // TODO
            //    ':Archive:DetailedResults:default',
            //),
        ];
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function formatTemplateFiles(): array
    {
        $files = parent::formatTemplateFiles();
        $key = parent::createEventKey($this->getEvent());

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
        $template->eventKey = parent::createEventKey($this->getEvent());
        return $template;
    }
}
