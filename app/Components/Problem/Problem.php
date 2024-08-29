<?php

declare(strict_types=1);

namespace App\Components\Problem;

use App\Models\Downloader\ProblemModel;
use App\Models\Downloader\ProblemService;
use App\Models\Downloader\SeriesModel;
use App\Modules\Core\Language;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class Problem extends DIComponent
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

    public function render(ProblemModel $problem, Language $language)
    {
        $this->template->series = $this->series;
        $this->template->problemService = $this->problemService;
        $this->template->problem = $problem;
        $this->template->language = $language;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'problem.latte');
    }
}
