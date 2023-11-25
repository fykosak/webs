<?php

declare(strict_types=1);


namespace App\Modules\Fykos\DefaultModule;

use Nette\Utils\DateTime;


class ProblemsPresenter extends BasePresenter
{


    public function renderDefault(): void
    {

        // Nahradit toto za načítání dat z databáze
        $fileContents1 = file_get_contents(__DIR__ . '/temp-solution.json');
        $data1 = json_decode($fileContents1, true);
        $fileContents2 = file_get_contents(__DIR__ . '/temp-solution2.json');
        $data2 = json_decode($fileContents2, true);
        $data = [$data1, $data2];

        $series = [
            "number" => 1,
            "year" => 37,
            "deadline" => new DateTime("2023-11-25 23:59:59"),
        ];

        $this->template->series = $series;

        $this->template->problems = $data;
    }
}
