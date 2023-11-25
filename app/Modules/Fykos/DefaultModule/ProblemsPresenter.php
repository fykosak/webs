<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

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

        $this->template->problems = $data;
    }
}
