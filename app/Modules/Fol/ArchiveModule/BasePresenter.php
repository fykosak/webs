<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Models\Downloader\EventService;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Template;
use Nette\Http\IResponse;

abstract class BasePresenter extends \App\Modules\Fol\Core\BasePresenter
{
    /** @persistent */
    public ?string $eventYear = null;

    private ModelEvent $event;
    protected EventService $eventService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
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
                $events = $this->eventService->getEventsByYear(
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

    protected function getNavItems(): array
    {
        return [
            new NavItem(
                new PageTitle(
                    null,
                    $this->csen('Archiv', 'History'),
                    'visible-sm-inline glyphicon glyphicon-info-sign'
                ), // TODO
                ':Default:Archive:default',
            ),
            new NavItem(
                new PageTitle(
                    null,
                    $this->csen('Týmy', 'Teams'),
                    'visible-sm-inline glyphicon glyphicon-info-sign'
                ), // TODO
                ':Archive:Teams:default',
            ),
            new NavItem(
                new PageTitle(
                    null,
                    $this->csen('Pořadí', 'Results'),
                    'visible-sm-inline glyphicon glyphicon-compressed'
                ), // TODO
                ':Archive:Results:default',
            ),
            new NavItem(
                new PageTitle(
                    null,
                    $this->csen('Ohlasy účastníků', 'Reports'),
                    'visible-sm-inline glyphicon glyphicon-exclamation-sign'
                ),
                // TODO
                ':Archive:Reports:default',
            ),
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
        return $template;
    }
}
