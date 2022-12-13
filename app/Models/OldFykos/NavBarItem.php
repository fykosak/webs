<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Nette\Application\UI\Component;
use Nette\Utils\Html;

final class NavBarItem
{
    public ?string $pageId;
    public ?string $icon;
    public ?string $title;
    /** @var self[] */
    public array $items;

    public function __construct(string $pageId, string $title, ?string $icon = null, array $items = [])
    {
        $this->pageId = $pageId;
        $this->icon = $icon;
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
        $span = Html::el('span');
        if (isset($this->icon)) {
            $span->addHtml(Html::el('i')->addAttributes(['class' => 'mx-2 ' . $this->icon]));
        }
        $span->addText($this->title);
        return $span;
    }
}
