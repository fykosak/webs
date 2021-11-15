<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Components\Navigation\Navigation;
use App\Models\Exceptions\UnderConstructionException;
use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends Presenter
{

    /** @persistent */
    public ?string $lang = null; // = 'cs';

    public GettextTranslator $translator;
    protected ServiceEventDetail $serviceEventDetail;

    public function injectServices(GettextTranslator $translator, ServiceEventDetail $serviceEventDetail): void
    {
        $this->translator = $translator;
        $this->serviceEventDetail = $serviceEventDetail;
    }

    protected GamePhaseCalculator $gamePhaseCalculator;

    public function injectGamePhaseCalculator(GamePhaseCalculator $calculator): void
    {
        $this->gamePhaseCalculator = $calculator;
    }

    /**
     * @throws UnsupportedLanguageException
     */
    protected function startUp(): void
    {
        parent::startup();
        $this->localize();
    }

    protected function createComponentNavigation(): Navigation
    {
        $navigation = new Navigation($this->getContext());
        foreach ($this->getNavItems() as $navItem) {
            $navigation->addNavItem($navItem);
        }
        return $navigation;
    }

    abstract protected function getNavItems(): array;

    final public function setPageTitle(PageTitle $pageTitle): void
    {
        $this->template->pageTitle = $pageTitle;
    }

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->lang = $this->lang;
        $template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $template->setTranslator($this->translator);

        return $template;
    }


// -------------- i18n ------------------

    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void
    {
        // Lang is null in error presenter because no rote rule was applied
        if (!isset($this->lang) || $this->lang == null) {
            $this->lang = "en"; // todo guess language by domain
        }

        $this->translator->setLang($this->lang);
    }

    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }

    public function formatTemplateFiles(): array
    {
        [$file,] = parent::formatTemplateFiles();
        return [
            str_replace('.latte', '.' . $this->lang . '.latte', $file),
            $file,
        ];
    }

    /**
     * @throws UnderConstructionException
     */
    protected function throwUnderConstructionException(): void
    {
        if ($this->getParameter('dev')) {
            return;
        }
        throw new UnderConstructionException();
    }
}
