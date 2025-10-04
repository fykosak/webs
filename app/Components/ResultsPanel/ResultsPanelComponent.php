<?php

declare(strict_types=1);

namespace App\Components\ResultsPanel;

use App\Components\ApiResults\ApiResultsComponent;
use App\Models\Downloader\EventModel;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

final class ResultsPanelComponent extends DIComponent
{
    public function __construct(Container $container, private readonly EventModel $event)
    {
        parent::__construct($container);
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentApiResults(): ApiResultsComponent
    {
        return new ApiResultsComponent($this->getContext(), $this->event->eventId);
    }

    public function render(bool $dark = false): void
    {
        $this->template->dark = $dark;
        $this->template->lang = $this->translator->lang;
        $this->template->event = $this->event;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'panel.latte');
    }
}
