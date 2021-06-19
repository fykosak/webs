<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class FaqPresenter extends BasePresenter {

    private FaqService $faqService;

    public function injectFaqService(FaqService $faqService){
        $this->faqService = $faqService;
    }

    private function loadQuestions(){
        $query = $this->faqService->getTable()->where('lang',$this->lang);

        // questions sorted by category
        $questions = [];

        foreach ($query as $question){
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

    public function renderDefault(): void
    {
        $this->setPagetitle(_('FAQ'));
        $this->changeViewByLang();

        $this->loadQuestions();
    }


}
