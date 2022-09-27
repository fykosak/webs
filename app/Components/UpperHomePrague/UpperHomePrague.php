<?php

declare(strict_types=1);

namespace App\Components\UpperHomePrague;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class UpperHomePrague extends BaseComponent
{
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function injectGamePhaseCalculator(GamePhaseCalculator $gamePhaseCalculator): void
    {
        $this->gamePhaseCalculator = $gamePhaseCalculator;
    }

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . "upperHomePrague.latte");
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->gamePhaseCalculator->getGameBegin());
    }
}
