<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;
use Tracy\Debugger;

final class AboutPresenter extends BasePresenter
{
    private FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function renderOrganizers(): void
    {
        $response = $this->downloader->download(new OrganizersRequest(1, 37));
        Debugger::barDump($response);
    }
}
