<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\ProblemManager;

use App\Models\Downloader\Models\Core\ProblemModel;
use App\Models\Downloader\Models\Core\ProblemTypes;
use App\Modules\Core\Language;

class PMProblemModel extends ProblemModel
{
    public int $problemId;
    public int $contestId;
    public int $seriesId;
    public int $seriesOrder;
    public int $typeId;
    public string $created;

    /**
     * @var string[] $topics
     */
    public array $topics;

    public array $metadata;

    public array $texts;

    public function getText(string $type, Language $lang): ?string
    {
        foreach ($this->texts as $text) {
            if ($text['type'] === $type && $text['lang'] === $lang->value) {
                return $text['html'];
            }
        }

        return null;
    }

    /**
     * Returns problem name by lang only if its html value exists.
     */
    public function getName(Language $lang): ?string
    {
        if (!array_key_exists('html', $this->metadata)) {
            return null;
        }

        if (!array_key_exists('name', $this->metadata['html'])) {
            return null;
        }

        if (!array_key_exists($lang->value, $this->metadata['html']['name'])) {
            return null;
        }

        // By default, HTML from PM contains paragraphs, remove them
        return str_replace(array("<p>","</p>"), "", $this->metadata['html']['name'][$lang->value]);
    }

    /**
     * Returns problem origin by lang only if its html value exists.
     */
    public function getOrigin(Language $lang): ?string
    {
        if (!array_key_exists('html', $this->metadata)) {
            return null;
        }

        if (!array_key_exists('origin', $this->metadata['html'])) {
            return null;
        }

        if (!array_key_exists($lang->value, $this->metadata['html']['origin'])) {
            return null;
        }

        return $this->metadata['html']['origin'][$lang->value];
    }

    public function getOrder(): int
    {
        return $this->seriesOrder;
    }

    public function getContestId(): int
    {
        return $this->contestId;
    }

    public function getPoints(): ?int
    {
        return $this->metadata['points'];
    }

    public function getType(): ProblemTypes
    {
        return ProblemTypes::from($this->typeId);
    }
}
