<?php

declare(strict_types=1);

namespace App\Components\UpperHomePrague;

use App\Components\Countdown\CountdownComponent;
use App\Models\Downloader\Models\EventModel;
use Fykosak\Utils\Components\DIComponent;
use Fykosak\Utils\DateTime\Phase;
use Nette\DI\Container;

final class UpperHomePrague extends DIComponent
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
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomePrague.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        if ($this->event->getRegistrationPeriod()->is(Phase::before)) {
            return new CountdownComponent(
                $this->container,
                $this->event->registrationBegin
            );
        }
        return new CountdownComponent($this->container, $this->event->game->begin);
    }
}
