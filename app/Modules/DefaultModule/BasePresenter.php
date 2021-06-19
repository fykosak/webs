<?php

namespace App\Modules\DefaultModule;

use App\Components\Navigation\Navigation;
use App\Components\Navigation\NavItem;
use Exception;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends Presenter {

    /** @persistent */
    public ?string $lang = null; // = 'cs';

    private string $customScript = '';

    protected GettextTranslator $translator;

    public function injectServices(GettextTranslator $translator): void {
        $this->translator = $translator;
    }

    protected function startUp(): void {
        parent::startup();
        $this->localize();
    }

    /**
     * @return Navigation
     * @throws Exception
     */
    protected function createComponentNavigation(): Navigation {
        $navigation = new Navigation($this->getContext());

        $navigation->addNavItem(new NavItem(':Default:AboutTheCompetition:default', [], _('O soutěži'), 'visible-sm-inline glyphicon glyphicon-info-sign'));
        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Archiv'), 'visible-sm-inline glyphicon glyphicon-compressed'));
        $navigation->addNavItem(new NavItem(':Default:Rules:default', [], _('Pravidla'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'));
        $navigation->addNavItem(new NavItem(':Default:Faq:default', [], _('FAQ'), 'visible-sm-inline glyphicon glyphicon-question-sign'));
        $navigation->addNavItem(new NavItem(':Default:HowToPlay:default', [], _('Návod'), 'visible-sm-inline glyphicon glyphicon-info-sign'));


        //if ($this->yearsService->isRegistrationStarted()) {
        $navigation->addNavItem(new NavItem(':Default:Team:list', [], _('Týmy'), 'visible-sm-inline glyphicon glyphicon-list'));
        //  if ($this->yearsService->isGameStarted()) {
//        $navigation->addNavItem(new NavItem(':Archive:Archive:results', [], _('Výsledky'), 'visible-sm-inline glyphicon glyphicon-stats'));
        // }
        //}

        // if ($this->yearsService->isRegistrationActive()) {
        //    if (!$this->getUser()->isLoggedIn()) {
        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Registrace'), 'visible - sm - inline glyphicon glyphicon-edit'));
        //    }
        // }
        return $navigation;
    }

    public function setPageTitle(string $pageTitle): void {
        $this->getTemplate()->pageTitle = $pageTitle;
    }

// ----- PROTECTED METHODS

    protected function createTemplate(): Template {
        $template = parent::createTemplate();
        $template->today = date("Y-m-d H:i:s");
        $template->lang = $this->lang;
        $template->customScript = '';
        $template->setTranslator($this->translator);

        return $template;
    }

    public function addCustomScript(string $script): void {
        $this->customScript .= $script;
    }

    public function getCustomScript(): string {
        return $this->customScript;
    }

    /* temporary hack for DI */

// -------------- l12n ------------------
    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void {
        $i18nConf = $this->context->parameters['i18n'];
        $this->detectLang($i18nConf);
        $this->translator->setLang($this->lang);
    }

    protected function detectLang($i18nConf): void {
        if (!isset($this->lang)) {
            $this->lang = $this->getHttpRequest()->detectLanguage($this->translator->getSupportedLanguages());
        }
        if (array_search($this->lang, $this->translator->getSupportedLanguages()) === false) {
            $this->lang = $i18nConf['defaultLang'];
        }
    }

    public function getOpenGraphLang(): ?string {
        return $this->getHttpRequest()->getHeader('X-Facebook-Locale');
    }

    protected function changeViewByLang(): void {
        $this->setView($this->getView() . '.' . $this->lang);
    }
}
