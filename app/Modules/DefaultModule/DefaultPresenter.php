<?php

namespace App\Modules\DefaultModule;

use Exception;
use Nette;

class DefaultPresenter extends BasePresenter {



    /**** FAQ part -- possibly move to a separate presenter when routing is rewritten ****/

    private Nette\Database\Explorer $database;

    public function injectDatabase(Nette\Database\Explorer $database){
        $this->database = $database;
    }

    public function renderFaq(): void {
        $this->loadQuestions();

        $this->setPagetitle(_('FAQ'));
        $this->changeViewByLang();
    }

    private function loadQuestions(){
        
        $questions = [];

        foreach ($this->database->table('faq') as $question){
            $category = $question->category;
            if (is_null($category)){
                $category = 'Other'; // TODO: correct for language
            }
            if (!isset($questions[$category])) {
                $questions[$category] = [];
            }
            $questions[$category][] = $question;
        }
        $this->template->questions = $questions;
    }

    /* end stuff for faq */




    /**
     * @return void
     * @throws Exception
     */
    public function renderDefault(): void {
        $this->setPagetitle(_('Mezinárodní soutež ve fyzice'));
     //   $this->template->year = $this->yearsService->findCurrent();
        $this->changeViewByLang();
    }

    public function renderLastYears(): void {
        $this->setPagetitle(_('Minulé ročníky'));
        $this->changeViewByLang();
    }

    public function renderRules(): void {
        $this->setPagetitle(_('Pravidla'));
        $this->changeViewByLang();
    }

    public function renderHowto(): void {
        $this->setPagetitle(_('Rychlý grafický návod ke hře'));
        $this->changeViewByLang();
    }

    public function renderList(): void {
        $this->setPageTitle(_('Přihlášené týmy'));
        $this->changeViewByLang();
    }

    public function renderTaskExamples(): void {
        $this->setPagetitle(_('Rozcvička'));
    }

    public function renderOtherEvents(): void {
        $this->setPagetitle(_('Další akce'));
        $this->changeViewByLang();
    }


}
