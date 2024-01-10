<?php

declare(strict_types=1);

namespace App\Components\UpperHomePrague;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use App\Models\NetteDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class UpperHomePrague extends BaseComponent
{
    protected ServiceEventDetail $serviceTeam;
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function __construct(Container $container)
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
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomePrague.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        if ($this->gamePhaseCalculator->isRegistration($this->gamePhaseCalculator::BEFORE)) {
            return new CountdownComponent($this->gamePhaseCalculator->getFKSDBEvent()->registrationBegin);
        }
        return new CountdownComponent($this->gamePhaseCalculator->getGameBegin());
    }
}
