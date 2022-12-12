<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

class BootstrapNavBar
{

    private array $data = [];

    private ?string $brand = null;

    private string $className;

    private string $id;

    public function __construct(string $id, string $className)
    {
        $this->id = $id;
        $this->className = $className;
    }

    public function addBrand(string $href = '', ?string $text = null, ?string $imageSrc = null): void
    {
        $this->brand = '<a class="navbar-brand" href="' . wl(cleanID($href)) . '">' .
        ($imageSrc ? '<img src="' . tpl_basedir() . $imageSrc .
            '" width="30" height="30" class="d-inline-block align-top" alt="">' : '')
        . $text ?? '' . '</a>';
    }


    public function render(): string
    {
        return '
<nav class="navbar navbar-toggleable-md ' . $this->className . '">' . ($this->brand ?? '') . '
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="' . '#mainNavbar' . $this->id . '"
    aria-controls="navbarSupportedContent"
    aria-expanded="false"
    aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar' . $this->id . '">' .
            join('', array_map(fn($item) => $this->renderItem($item['data'], $item['class']), $this->data)) . '
    </div>
</nav>';
    }

    /**
     * @return NavBarItem[]
     */
    private function parseMenuFile(string $filename): array
    {
        $filePath = wikiFN($filename);
        $data = [];
        if (file_exists($filePath)) {
            $lines = array_filter(
                file($filePath),
                function ($line) {
                    return preg_match('/^\s+\*/', $line);
                }
            );

            $numLines = count($lines);
            for ($i = 0; $i < $numLines; $i++) {
                if (!$lines[$i]) {
                    continue;
                }
                [$prefix, $content] = explode('*', $lines[$i]);
                $level = (int)strlen($prefix) / 2;
                $level = ($level > 2) ? 2 : $level;

                if (!preg_match('/\s*\[\[[^\]]+\]\]/', $content)) {
                    continue;
                }
                $content = str_replace([']', '['], '', trim($content));
                [$id, $content, $icon] = explode('|', $content);
                $data[] = new NavBarItem($id, $content, $level, $icon);
            }
        }
        return $data;
    }

    public function addMenuText(string $file, ?string $class = null): void
    {
        $pageLang = $conf['lang'];
        $menuFileName = 'system/' . $file . '_' . $pageLang;

        $this->data[] = [
            'class' => 'nav ' . $class ?? '',
            'data' => $this->parseMenuFile($menuFileName),
        ];
    }

    public function addLangSelect(?string $class = null): void
    {
        $data = [];
        if (
            !isset($conf['available_lang']) || !is_countable($conf['available_lang']) || !count($conf['available_lang'])
        ) {
            return;
        }
        $data[] = new NavBarItem(null, '<span class="fa fa-language"></span>', 1, null);

        foreach ($conf['available_lang'] as $currentLang) {
            $data[] = new NavBarItem(
                null, '<a
                href="' . $currentLang['content']['url'] . '"
                class="dropdown-item ' . $currentLang['content']['class'] . ' ' .
                ($currentLang['code'] == $conf['lang'] ? 'active' : '') . '"
                ' . $currentLang['content']['more'] . '
                >' . $currentLang['content']['text'] . ' </a> ', 2, null
            );
        }
        $this->data[] = [
            'class' => 'nav ' . ($class ?? ''),
            'data' => $data,
        ];
    }


    /**
     * @param NavBarItem[] $data
     * @param string $class
     * @return string
     */
    private function renderItem(array $data, string $class): string
    {
        $inLI = false;
        $inUL = false;

        $html = ' <div class="nav navbar-nav ' . $class . '" > ';

        foreach ($data as $k => $item) {
            $link = $item->getLink();
            $title = (isset($item->icon) ? ' <span class="' . $item->icon . '" ></span > ' : '') . $item->content;
            if ($item->level == 1) {
                if ($inUL) {
                    $inUL = false;
                    $html .= '</div>';
                }
                if ($inLI) {
                    $inLI = false;
                    $html .= '</div>';
                }
                /* is next level 2? */
                if (isset($data[$k + 1]) && $data[$k + 1]->level == 2) {
                    $inLI = true;
                    $html .= '<div class="dropdown nav-item"><a href="' . $link .
                        '" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >' .
                        $title . '<span class="caret"></span></a>';
                } else {
                    $html .= '<a class="nav-item nav-link" href="' . $link . '">' . $title . '</a>';
                }
            } elseif ($item->level == 2) {
                if (!$inUL) {
                    $inUL = true;
                    $html .= '<div class="dropdown-menu" role="menu">' . "\n";
                }

                if (isset($item->pageId)) {
                    $html .= '<a class="dropdown-item" href="' . $link . '">' . $title . '</a>';
                } else {
                    $html .= $title;
                }
            }
        }
        if ($inUL) {
            $html .= '</div>';
        }
        if ($inLI) {
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}
