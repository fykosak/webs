<?php

namespace App\Modules\DefaultModule;

use Nette;


class ReportPresenter extends BasePresenter
{
    private \App\Models\ORM\ReportService $reportService;

    public function __construct(\App\Models\ORM\ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function renderDefault(): void
    {
        $this->template->reports = $this->reportService->getTable()->where('lang', $this->lang);
        $this->setPageTitle('Ohlasy účastníků');
        $this->changeViewByLang();
    }

// ...

}
