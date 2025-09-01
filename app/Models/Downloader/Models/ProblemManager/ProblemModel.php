<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\ProblemManager;

class ProblemModel
{
    public int $problemId;
    public int $seriesId;
    public int $seriesOrder;
    public int $typeId;
    public string $created;

    /**
     * @var int[] $topics
     */
    public array $topics;

    public array $metadata;

    public array $texts;

    public static function getTopicLabel(string $topic, string $lang): string
    {
        // TODO
        return $topic;
    }

    public function getLabel(): string
    {
        // TODO
        return (string)$this->seriesOrder;
    }
    public function getIcon(): string
    {
        // TODO
        return '';
    }

    public function getText(string $type, string $lang)
    {
        foreach ($this->texts as $text) {
            if ($text['type'] === $type && $text['lang'] === $lang) {
                return $text;
            }
        }

        return null;
    }
}
