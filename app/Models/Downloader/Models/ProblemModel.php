<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

class ProblemModel
{
    public string $contest;
    public int $year;
    public int $series;
    public int $number;
    /**
     * @var string[]
     */
    public array $name;
    /**
     * @var string[]
     */
    public ?array $origin = [];
    public ?int $points; // null for backwards compatibility
    /**
     * @var string[]
     */
    public array $topics;
    /**
     * @var array[][]
     */
    public array $authors;
    /**
     * @var int[]
     */
    public ?array $studyYears;
    /**
     * @var string[]
     */
    public array $task;
    /**
     * @var string[]
     */
    public array $solution;
    public ?float $machineResult;
    /**
     * @var string[]
     */
    public array $humanResult;

    public static function getTopicLabel(string $topic, string $lang): string
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
                "cs" => "Elektrina"
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

        return $topicLabels[$lang] ?? $topic;
    }

    public function getLabel(): string
    {
        if ($this->contest === 'fykos') {
            switch ($this->number) {
                case 6:
                    return 'P';
                case 7:
                    return 'E';
                case 8:
                    return 'S';
            }
        } elseif ($this->contest === 'vyfuk') {
            switch ($this->number) {
                case 6:
                    return 'E';
                case 7:
                    return 'V';
            }
        }

        return (string)$this->number;
    }

    public function getIcon(): string
    {
        if ($this->contest === 'fykos') {
            return match ($this->number) {
                1, 2 => 'fas fa-smile',
                3, 4, 5 => 'fas fa-brain',
                6 => 'fas fa-lightbulb',
                7 => 'fas fa-flask',
                8 => 'fas fa-book',
                default => ''
            };
        } elseif ($this->contest === 'vyfuk') {
            return match ($this->number) {
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
