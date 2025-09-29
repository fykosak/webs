<?php

declare(strict_types=1);

namespace App\Components\UpperHomeBeforeRegistration;

use App\Components\Countdown\CountdownComponent;
use App\Models\Downloader\EventModel;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

final class UpperHomeBeforeRegistrationComponent extends DIComponent
{
    public function __construct(
        Container $container,
        private readonly EventModel $event
    ) {
        parent::__construct($container);
    }

    public function render(): void
    {
        $this->template->event = $this->event;
        $this->template->lang = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeBeforeRegistration.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent(
            $this->getContext(),
            $this->event->registrationBegin
        );
    }
}
