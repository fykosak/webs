<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\Core;

enum ProblemTypes: int
{
    case FykosEasy = 1;
    case FykosHard = 2;
    case FykosOpen = 3;
    case FykosExperimental = 4;
    case FykosSerial = 5;
    case VyfukJednicka = 9;
    case VyfukMatematika = 10;
    case VyfukExperiment = 12;
    case VyfukSerial = 13;
    case VyfukKviz = 14;
    case VyfukOdhadovaci = 15;
    case VyfukPrExp = 16;
    case VyfukLehkaFyzika = 20;
    case VyfukTezkaFyzika = 21;

    public function getIcon(): string
    {
        return match ($this) {
            ProblemTypes::FykosEasy => 'fas fa-smile',
            ProblemTypes::FykosHard => 'fas fa-brain',
            ProblemTypes::FykosOpen => 'fas fa-lightbulb',
            ProblemTypes::FykosExperimental => 'fas fa-flask',
            ProblemTypes::FykosSerial => 'fas fa-book',
            ProblemTypes::VyfukJednicka => 'fas fa-pencil',
            ProblemTypes::VyfukMatematika => 'fas fa-calculator',
            ProblemTypes::VyfukExperiment, ProblemTypes::VyfukPrExp => 'fas fa-flask',
            ProblemTypes::VyfukSerial => 'fas fa-book',
            ProblemTypes::VyfukKviz => 'fas fa-list-ul',
            ProblemTypes::VyfukOdhadovaci => 'fas fa-lightbulb',
            ProblemTypes::VyfukLehkaFyzika => 'fas fa-magnet',
            ProblemTypes::VyfukTezkaFyzika => 'fas fa-cogs'
        };
    }
}
