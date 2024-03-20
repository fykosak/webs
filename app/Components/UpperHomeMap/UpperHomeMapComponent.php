<?php

declare(strict_types=1);

namespace App\Components\UpperHomeMap;

use App\Components\Countdown\CountdownComponent;
use App\Components\Map\MapComponent;
use App\Models\GamePhaseCalculator;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

final class UpperHomeMapComponent extends DIComponent
{
    private GamePhaseCalculator $gamePhaseCalculator;
    private ModelEvent $event;

    public function __construct(Container $container, GamePhaseCalculator $calculator, ModelEvent $event)
    {
        parent::__construct($container);
        $this->gamePhaseCalculator = $calculator;
        $this->event = $event;
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->translator->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeMap.latte');
    }

    protected function createComponentMap(): MapComponent
    {
        return new MapComponent($this->getContext(), $this->gamePhaseCalculator, $this->event);
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->container, $this->gamePhaseCalculator->getGameBegin());
    }
}
