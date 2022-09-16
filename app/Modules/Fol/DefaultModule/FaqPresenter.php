<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Models\ORM\FaqModel;
use App\Models\ORM\FaqService;

class FaqPresenter extends BasePresenter
{

    private FaqService $faqService;

    public function injectFaqService(FaqService $faqService): void
    {
        $this->faqService = $faqService;
    }

    private function loadQuestions(): void
    {
        $query = $this->faqService->getTable()->where('lang', $this->lang);

        // questions sorted by category
        $questions = [];
        /** @var FaqModel $question */
        foreach ($query as $question) {
            $category = $question->category;

            if (!isset($questions[$category])) {
                $questions[$category] = [];
            }
            $questions[$category][] = $question;
        }

        $this->template->questions = $questions;
    }

    public function renderDefault(): void
    {
        $this->loadQuestions();
    }
}
