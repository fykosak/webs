<?php

declare(strict_types=1);

namespace App\Components\Problem;

use App\Models\Downloader\Models\ProblemManager\ProblemModel;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
use App\Models\Downloader\Services\ProblemService;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class ProblemComponent extends DIComponent
{
    private readonly ProblemService $problemService;

    public function __construct(Container $container, private readonly SeriesModel $series)
    {
        parent::__construct($container);
    }

    public function injectServiceProblem(ProblemService $problemService): void
    {
        $this->problemService = $problemService;
    }

    public function render(ProblemModel $problem)
    {
        $this->template->series = $this->series;
        $this->template->problemService = $this->problemService;
        $this->template->problem = $problem;
        $this->template->language = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'problem.latte');
    }
}
