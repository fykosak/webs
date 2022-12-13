<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Fykosak\Utils\UI\PageTitle;
use Nette\Application\UI\Component;
use Nette\Utils\Html;

final class NavBarItem
{
    public ?string $pageId;
    public ?string $icon;
    public PageTitle $title;
    /** @var self[] */
    public array $items;

    public function __construct(string $pageId, PageTitle $title, array $items = [])
    {
        $this->pageId = $pageId;
        $this->title = $title;
        $this->items = $items;
    }

    public function renderLink(Component $component): string
    {
        if (preg_match('#https?://#', $this->pageId)) {
            return htmlspecialchars($this->pageId);
        }
        return str_replace(':', '/', $this->pageId);
        //   return $component->link($this->pageId);
    }

    public function renderTitle(): Html
    {
        return $this->title->toHtml();
    }
}
