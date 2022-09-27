<?php

declare(strict_types=1);

namespace App\Components\UpperHomeMap;

use App\Components\Countdown\CountdownComponent;
use App\Components\Map\MapComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class UpperHomeMapComponent extends BaseComponent
{
    protected ServiceEventDetail $serviceTeam;
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function __construct(Container $container, GamePhaseCalculator $calculator)
    {
        parent::__construct($container);
        $this->gamePhaseCalculator = $calculator;
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeMap.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentMap(): MapComponent
    {
        return new MapComponent($this->getContext(), $this->gamePhaseCalculator->getFKSDBEvent()->eventId);
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->gamePhaseCalculator->getGameBegin());
    }
}
