<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Services\ServiceEventList;
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
    protected readonly ServiceEventList $serviceEvent;

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
                    [$this->context->getParameters()['eventTypeId']],
                    intval($year)
                );
                if (count($events)) {
                    $event = isset($month) ? reset($events) : end($events);
                }
            }
            if (!isset($event)) {
                throw new BadRequestException(
                    $this->csen('Akce nenalezena', 'Event not found'),
                    IResponse::S404_NOT_FOUND
                );
            }
            $this->event = $event;
        }
        return $this->event;
    }

    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        return [
            new NavItem(
                new PageTitle(
                    $this->csen('Archiv', 'Archive'),
                    'visible-sm-inline glyphicon glyphicon-info-sign'
                ), // TODO
                ':Default:Archive:',
            ),
            new NavItem(
                new PageTitle(
                    $this->csen('Týmy', 'Teams'),
                    'visible-sm-inline glyphicon glyphicon-info-sign'
                ), // TODO
                ':Archive:Teams:default',
            ),
            new NavItem(
                new PageTitle(
                    $this->csen('Pořadí', 'Results'),
                    'visible-sm-inline glyphicon glyphicon-compressed'
                ), // TODO
                ':Archive:Results:default',
            ),
            //new NavItem(
            //    new PageTitle( _('detailed_results.menu'), 'visible-sm-inline glyphicon glyphicon-compressed'),
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
            str_replace('.latte', '.' . $key . '.' . $this->language->value . '.latte', end($files)),
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
