<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {

        $this->template->events = [
            "previous" => [
                "name" => "Fyziklání Online",
                "date-text" => "21. 11. 2023"
            ],
            "upcoming" => [
                "name" => "Deadline 3. série",
                "date-text" => "2. 1. 2024"
            ],
            "next" => [
                "name" => "Fyziklání",
                "date-text" => "16. 2. 2023"
            ]
        ];

    }
}
