<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\Archive;

use App\Models\Downloader\Models\Core\ProblemModel;
use App\Models\Downloader\Services\ProblemService;
use App\Modules\Core\Language;
use App\Models\Downloader\Models\Core\ProblemTypes;

class ArchiveProblemModel extends ProblemModel
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

    public function getText(string $type, Language $lang): ?string
    {
        if ($type === 'task') {
            return $this->task[$lang->value];
        }

        return null;
    }

    public function getName(Language $lang): ?string
    {
        if (array_key_exists($lang->value, $this->name)) {
            return $this->name[$lang->value];
        }

        return null;
    }

    public function getOrigin(Language $lang): ?string
    {
        if (array_key_exists($lang->value, $this->origin)) {
            return $this->origin[$lang->value];
        }

        return null;
    }

    public function getOrder(): int
    {
        return $this->number;
    }

    public function getContestId(): int
    {
        return match ($this->contest) {
            'fykos' => ProblemService::FYKOS,
            'vyfuk' => ProblemService::VYFUK,
            default => -1 // should never occur
        };
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function getType(): ?ProblemTypes
    {
        if ($this->contest === 'fykos') {
            return match ($this->number) {
                1, 2 => ProblemTypes::FykosEasy,
                3, 4, 5 => ProblemTypes::FykosHard,
                6 => ProblemTypes::FykosOpen,
                7 => ProblemTypes::FykosExperimental,
                8 => ProblemTypes::FykosSerial,
                default => null
            };
        }

        return null;
    }
}
