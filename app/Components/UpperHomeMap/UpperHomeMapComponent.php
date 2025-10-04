<?php

declare(strict_types=1);

namespace App\Components\UpperHomeMap;

use App\Components\Countdown\CountdownComponent;
use App\Components\Map\MapComponent;
use App\Models\Downloader\EventModel;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

final class UpperHomeMapComponent extends DIComponent
{
    public function __construct(
        Container $container,
        private readonly EventModel $event
    ) {
        parent::__construct($container);
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->translator->lang;
        $this->template->event = $this->event;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeMap.latte');
    }

    protected function createComponentMap(): MapComponent
    {
        return new MapComponent($this->getContext(), $this->event);
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->container, $this->event->game->begin);
    }
}
