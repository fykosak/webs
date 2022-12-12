<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

/**
 * @property-read string $headline
 * @property-read string $text
 * @property-read array[] $buttons
 * @property-read string[]|int[] backgrounds
 */
class JumbotronItem
{
    public string $headline;
    public string $text;
    public array $backgrounds = [];
    public array $buttons = [];

    public function __construct(array $data)
    {
        $this->headline = $data['headline'];
        $this->text = $data['text'];
        $this->buttons = $data['buttons'];
        $this->backgrounds = $data['backgrounds'];
    }
}
