<?php

namespace App\Modules\DefaultModule;

use App\Components\Navigation\Navigation;
use App\Components\Navigation\NavItem;
use App\Model\Translator\GettextTranslator;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;

abstract class BasePresenter extends Presenter {


    /** @persistent */
    public $lang; // = 'cs';

    private string $customScript = '';

    protected ITranslator $translator;

    public function injectServices(ITranslator $translator): void {
        $this->translator = $translator;
    }

    protected function startUp(): void {
        parent::startup();
        $this->localize();
    }

    /**
     * @return Navigation
     * @throws \Exception
     */
    protected function createComponentNavigation(): Navigation {
        $navigation = new Navigation($this->getContext());

        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Archiv'), 'visible-sm-inline glyphicon glyphicon-compressed'));
        $navigation->addNavItem(new NavItem(':Default:Default:rules', [], _('Pravidla'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'));
        $navigation->addNavItem(new NavItem(':Default:Default:faq', [], _('FAQ'), 'visible-sm-inline glyphicon glyphicon-question-sign'));
        $navigation->addNavItem(new NavItem(':Default:Default:howto', [], _('Návod'), 'visible-sm-inline glyphicon glyphicon-info-sign'));

        //if ($this->yearsService->isRegistrationStarted()) {
        $navigation->addNavItem(new NavItem(':Default:Default:chat', [], _('Fórum'), 'visible-sm-inline glyphicon glyphicon-comment'));
        $navigation->addNavItem(new NavItem(':Default:Default:list', [], _('Týmy'), 'visible-sm-inline glyphicon glyphicon-list'));
        //  if ($this->yearsService->isGameStarted()) {
        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Výsledky'), 'visible-sm-inline glyphicon glyphicon-stats'));
        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Nástěnka'), 'visible-sm-inline glyphicon glyphicon-pushpin'));
        //    if ($this->getUser()->isLoggedIn()) {
        $navigation->addNavItem(new NavItem(':Default:Default:default', [], _('Hra'), 'visible-sm-inline glyphicon glyphicon-tower'));
        //    }
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

    protected function createTemplate(): ITemplate {

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

    protected function localize(): void {
        $i18nConf = $this->context->parameters['i18n'];
        $this->detectLang($i18nConf);
        $locale = isset(GettextTranslator::$locales[$this->lang]) ? GettextTranslator::$locales[$this->lang] : 'cs_CZ.utf-8';

        putenv("LANGUAGE=$locale");
        setlocale(LC_MESSAGES, $locale);
        setlocale(LC_TIME, $locale);
        bindtextdomain('messages', $i18nConf['dir']);
        bind_textdomain_codeset('messages', "utf-8");
        textdomain('messages');
    }

    protected function detectLang($i18nConf): void {
        if (!isset($this->lang)) {
            $this->lang = $this->getHttpRequest()->detectLanguage(GettextTranslator::getSupportedLangs());
        }
        if (array_search($this->lang, GettextTranslator::getSupportedLangs()) === false) {
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
