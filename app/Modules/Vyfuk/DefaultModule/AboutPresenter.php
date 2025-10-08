<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\ContestsRequest;
use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;

class AboutPresenter extends BasePresenter
{
    public readonly FKSDBDownloader $FKSDBDownloader;

    public function injectDownloader(FKSDBDownloader $FKSDBDownloader): void
    {
        $this->FKSDBDownloader = $FKSDBDownloader;
    }

    /**
     * @throws \Throwable
     */
    public function renderOrganizers(): void
    {
        $allOrganizers = $this->FKSDBDownloader->download(new OrganizersRequest(2));
        $currentOrganizers = [];

        if ($allOrganizers !== []) {
            $currentOrganizers = array_filter(
                $allOrganizers,
                fn(array $organizer): bool => $organizer['state'] == 'active'
                && $organizer['showOnWeb']
            );

            // sort by order
            usort($currentOrganizers, function (array $a, array $b): int {
                if ($a['order'] === $b['order']) {
                    return implode(' ', array_reverse(explode(' ', $a['name']))) <=> implode(' ', array_reverse(explode(' ', $b['name'])));
                }
                return $b['order'] <=> $a['order'];
            });
        }
        $this->template->organizers = $currentOrganizers;
    }
}
