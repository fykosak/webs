<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;

class SchedulePresenter extends BasePresenter
{
    private ServiceEventDetail $serviceEvent;

    public function inject(ServiceEventDetail $serviceEvent): void
    {
        $this->serviceEvent = $serviceEvent;
    }

    public function renderDetail(): void
    {
        $this->template->data = $this->serviceEvent->getSchedule(173);
    }

}
