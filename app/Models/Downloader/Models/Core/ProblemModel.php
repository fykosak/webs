<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\Core;

use App\Models\Downloader\Services\ProblemService;
use App\Modules\Core\Language;

abstract class ProblemModel
{
    abstract public function getText(string $type, Language $lang): ?string;
    abstract public function getName(Language $lang): ?string;
    abstract public function getOrigin(Language $lang): ?string;
    abstract public function getOrder(): int;
    abstract public function getContestId(): int;
    abstract public function getPoints(): ?int;

    public static function getTopicLabel(string $topic, Language $lang): string
    {
        $topicLabels = match ($topic) {
            "mechHmBodu" => [
                "cs" => "Mechanika hmotného bodu",
                "en" => "Mechanics of a point mass"
            ],
            "mechTuhTel" => [
                "cs" => "Mechanika tuhého tělesa",
                "en" => "Mechanics of a rigid body"
            ],
            "hydroMech" => [
                "cs" => "Hydromechanika",
                "en" => "Hydromechanics"
            ],
            "mechPlynu" => [
                "cs" => "Mechanika plynu",
                "en" => "Gas mechanics"
            ],
            "gravPole" => [
                "cs" => "Gravitační pole",
                "en" => "Gravitational field"
            ],
            "kmitani" => [
                "cs" => "Kmitání",
                "en" => "Oscillations"
            ],
            "vlneni" => [
                "cs" => "Vlnění",
                "en" => "Waves"
            ],
            "molFyzika" => [
                "cs" => "Molekulová fyzika",
                "en" => "Molecular physics"
            ],
            "termoDyn" => [
                "cs" => "Termodynamika",
                "en" => "Thermodynamics"
            ],
            "statFyz" => [
                "cs" => "Statistická fyzika",
                "en" => "Statistical physics"
            ],
            "optikaGeom" => [
                "cs" => "Geometrická optika",
                "en" => "Geometrical optics"
            ],
            "optikaVln" => [
                "cs" => "Vlnová optika",
                "en" => "Wave optics"
            ],
            "elProud" => [
                "cs" => "Elektrický proud",
                "en" => "Electric current"
            ],
            "elPole" => [
                "cs" => "Elektrické pole",
                "en" => "Electric field"
            ],
            "magPole" => [
                "cs" => "Magnetické pole",
                "en" => "Magnetic field"
            ],
            "relat" => [
                "cs" => "Relativita",
                "en" => "Relativity"
            ],
            "kvantFyz" => [
                "cs" => "Kvantová fyzika",
                "en" => "Quantum physics"
            ],
            "jadFyz" => [
                "cs" => "Jaderná fyzika",
                "en" => "Nuclear physics"
            ],
            "astroFyz" => [
                "cs" => "Astrofyzika",
                "en" => "Astrophysics"
            ],
            "matematika" => [
                "cs" => "Matematika",
                "en" => "Mathematics"
            ],
            "chemie" => [
                "cs" => "Chemie",
                "en" => "Chemistry"
            ],
            "biofyzika" => [
                "cs" => "Biofyzika",
                "en" => "Biophysics"
            ],
            "other" => [
                "cs" => "Ostatní",
                "en" => "Other"
            ],
            "kinematika" => [
                "cs" => "Kinematika"
            ],
            "dynamika" => [
                "cs" => "Dynamika"
            ],
            "energie" => [
                "cs" => "Energie"
            ],
            "gravitace" => [
                "cs" => "Gravitace"
            ],
            "teplo" => [
                "cs" => "Gravitace"
            ],
            "astronomie" => [
                "cs" => "Astronomie"
            ],
            "optika" => [
                "cs" => "Optika"
            ],
            "elektrina" => [
                "cs" => "Elektřina"
            ],
            "odhady" => [
                "cs" => "Odhady"
            ],
            "geometrie" => [
                "cs" => "Geometrie"
            ],
            "programovani" => [
                "cs" => "Programování"
            ],
            "logika" => [
                "cs" => "Logika"
            ],
            "experimenty" => [
                "cs" => "Experimenty"
            ],
            default => $topic
        };

        return $topicLabels[$lang->value] ?? $topic;
    }

    public function getLabel(): string
    {
        if ($this->getContestId() === ProblemService::FYKOS) {
            switch ($this->getOrder()) {
                case 6:
                    return 'P';
                case 7:
                    return 'E';
                case 8:
                    return 'S';
            }
        } elseif ($this->getContestId() === ProblemService::VYFUK) {
            switch ($this->getOrder()) {
                case 6:
                    return 'E';
                case 7:
                    return 'V';
            }
        }

        return (string)$this->getOrder();
    }

    public function getIcon(): string
    {
        if ($this->getContestId() === ProblemService::FYKOS) {
            return match ($this->getOrder()) {
                1, 2 => 'fas fa-smile',
                3, 4, 5 => 'fas fa-brain',
                6 => 'fas fa-lightbulb',
                7 => 'fas fa-flask',
                8 => 'fas fa-book',
                default => ''
            };
        } elseif ($this->getContestId() === ProblemService::VYFUK) {
            return match ($this->getOrder()) {
                1 => 'fas fa-pencil',
                2 => 'fas fa-calculator',
                3 => 'fas fa-magnet',
                4 => 'fas fa-cogs',
                5 => 'fas fa-lightbulb',
                6 => 'fas fa-flask',
                7 => 'fas fa-book',
                default => ''
            };
        }

        return '';
    }
}
