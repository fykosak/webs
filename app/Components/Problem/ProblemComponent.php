<?php

namespace App\Components\Problem;

use App\Models\ORM\Problems\ProblemModel;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class ProblemComponent extends BaseComponent
{
    private string $lang;

    public function __construct(Container $container, string $lang)
    {
        parent::__construct($container);
        $this->lang = $lang;
    }

    public function render(ProblemModel $model)
    {
        $this->template->model = $model;
        $this->template->localisedData = $model->getLocalizedData($this->lang);
        $this->template->render(__DIR__ . '/problem.latte');
    }
}
