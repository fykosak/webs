<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

class FaqPresenter extends BasePresenter
{
    private function loadQuestions(): void
    {
        $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'faq.json');
        $query = json_decode($data);
        $query = array_filter($query, function ($item) {
            return $item->lang === $this->lang;
        });

        // questions sorted by category
        $questions = [];
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
