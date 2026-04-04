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
    abstract public function getType(): ?ProblemTypes;

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
            "kalorimetrie" => [
                "cs" => "Kalorimetrie"
            ],
            "astrofyzika" => [
                "cs" => "Astrofyzika"
            ],
            "energie" => [
                "cs" => "Energie"
            ],
            "optika" => [
                "cs" => "Optika"
            ],
            "geometrie" => [
                "cs" => "Geometrie"
            ],
            "mechanika hmotného bodu" => [
                "cs" => "Mechanika hmotného bodu"
            ],
            "mechanika tuhého tělesa" => [
                "cs" => "Mechanika tuhého tělesa"
            ],
            "mechanika kapalin" => [
                "cs" => "Mechanika kapalin"
            ],
            "deformace" => [
                "cs" => "Deformace"
            ],
            "termodynamika" => [
                "cs" => "Termodynamika"
            ],
            "jaderná fyzika" => [
                "cs" => "Jaderná fyzika"
            ],
            "elektrický proud" => [
                "cs" => "Elektrický proud"
            ],
            "elektromagnetismus" => [
                "cs" => "Elektromagnetismus"
            ],
            "kmitání a vlnění" => [
                "cs" => "Kmitání a vlnění"
            ],
            "síly" => [
                "cs" => "Síly"
            ],
            "ostatní" => [
                "cs" => "Ostatní"
            ],
            default => $topic
        };

        return $topicLabels[$lang->value] ?? $topic;
    }

    public function getLabel(): string
    {
        return match ($this->getType()) {
            ProblemTypes::FykosOpen => 'P',
            ProblemTypes::FykosExperimental => 'E',
            ProblemTypes::FykosSerial => 'S',
            ProblemTypes::VyfukExperiment, ProblemTypes::VyfukPrExp => 'E',
            ProblemTypes::VyfukSerial => 'V',
            default => (string)$this->getOrder()
        };
    }
}
