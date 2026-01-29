<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Models\Downloader\EventOrganizersRequest;
use App\Models\Downloader\FKSDBDownloader;

class AboutTheCompetitionPresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    public function renderOrganizers(): void
    {
        $organizers = $this->downloader->download(new EventOrganizersRequest($this->getNewestEvent()->eventId));
        usort(
            $organizers,
            fn(array $a, array $b) => $b['order'] <=> $a['order']
        );
        $this->template->organizers = $organizers;
    }
}
