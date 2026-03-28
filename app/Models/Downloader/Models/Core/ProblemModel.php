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
    abstract public function getTypeId(): ?int;

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
            return match ($this->getTypeId()) {
                3 => 'P',
                4 => 'E',
                5 => 'S',
                default => (string)$this->getOrder()
            };
        } elseif ($this->getContestId() === ProblemService::VYFUK) {
            return match ($this->getTypeId()) {
                12 => 'E',
                13 => 'V',
                default => (string)$this->getOrder()
            };
        }

        return (string)$this->getOrder();
    }

    public function getIcon(): string
    {
        if ($this->getContestId() === ProblemService::FYKOS) {
            return match ($this->getTypeId()) {
                1 => 'fas fa-smile', /*easy*/
                2 => 'fas fa-brain', /*hard*/
                3 => 'fas fa-lightbulb', /*open*/
                4 => 'fas fa-flask', /*experimental*/
                5 => 'fas fa-book', /*serial*/
                default => ''
            };
        } elseif ($this->getContestId() === ProblemService::VYFUK) {
            return match ($this->getTypeId()) {
                9 => 'fas fa-pencil', /*jednička*/
                10 => 'fas fa-calculator', /*matematika*/
                12, 16 => 'fas fa-flask', /*experiment, pr. exp.*/
                13 => 'fas fa-book', /*seriál*/
                14 => 'fas fa-list-ul', /*kvíz*/
                15 => 'fas fa-lightbulb', /*odhadovací*/
                20 => 'fas fa-magnet', /*lehká fyzika*/
                21 => 'fas fa-cogs', /*těžká fyzika*/
                default => ''
            };
        }

        return '';
    }
}
