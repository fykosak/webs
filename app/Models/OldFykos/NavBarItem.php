<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

class NavBarItem
{
    public ?string $pageId;
    public ?string $icon;
    public int $level;
    public ?string $content;

    public function __construct(?string $pageId, ?string $content, int $level, ?string $icon)
    {
        $this->pageId = $pageId;
        $this->icon = $icon;
        $this->level = $level;
        $this->content = $content;
    }

    public function getLink(): string
    {
        if (preg_match('#https?://#', $this->pageId)) {
            return htmlspecialchars($this->pageId);
        }
        return wl(cleanID($this->pageId));
    }
}
