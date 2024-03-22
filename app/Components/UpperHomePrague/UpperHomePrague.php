<?php

declare(strict_types=1);

namespace App\Components\UpperHomePrague;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Fykosak\Utils\Components\DIComponent;
use Fykosak\Utils\DateTime\Phase;
use Nette\DI\Container;

final class UpperHomePrague extends DIComponent
{
    private GamePhaseCalculator $gamePhaseCalculator;

    public function __construct(Container $container,private readonly ModelEvent $event)
    {
        parent::__construct($container);
    }

    public function injectGamePhaseCalculator(GamePhaseCalculator $gamePhaseCalculator): void
    {
        $this->gamePhaseCalculator = $gamePhaseCalculator;
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->translator->lang;
        $this->template->event = $this->event;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomePrague.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        if (
            $this->gamePhaseCalculator->getFKSDBEvent()
                ->getRegistrationPeriod()
                ->is(Phase::before)
        ) {
            return new CountdownComponent(
                $this->container,
                $this->gamePhaseCalculator->getFKSDBEvent()->registrationBegin
            );
        }
        return new CountdownComponent($this->container, $this->gamePhaseCalculator->getGameBegin());
    }
}
