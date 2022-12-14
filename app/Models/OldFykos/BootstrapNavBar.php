<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class BootstrapNavBar extends BaseComponent
{

    private array $data = [];

    private ?string $brand = null;

    private string $className;

    private string $id;

    public function __construct(Container $container, string $id, string $className)
    {
        parent::__construct($container);
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


    public function render(): void
    {
        $this->template->brand = $this->brand;
        $this->template->className = $this->className;
        $this->template->data = $this->data;
        $this->template->id = 'mainNavbar' . $this->id;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'navbar.latte');
    }

    public function addMenuText(array $items, ?string $class = null): void
    {
        $this->data[] = [
            'class' => 'nav ' . $class ?? '',
            'data' => $items,
        ];
    }
}
