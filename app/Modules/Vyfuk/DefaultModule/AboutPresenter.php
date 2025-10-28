<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

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
                fn (array $organizer): bool => $organizer['state'] === 'active'
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
     public function renderAllPastOrganizers(): void
     {
         $allOrganizers = $this->FKSDBDownloader->download(new OrganizersRequest(2));
         $AllPastOrganizers = [];

         if ($allOrganizers !== []) {
             $AllPastOrganizers = array_filter(
                 $allOrganizers,
                 fn (array $organizer): bool => $organizer['state'] === 'inactive'
                     && $organizer['showOnWeb']
             );

             // sort by order
             usort($AllPastOrganizers, function (array $a, array $b): int {
                 if ($a['since'] === $b['since']) {
                     return implode(' ', array_reverse(explode(' ', $a['name']))) <=> implode(' ', array_reverse(explode(' ', $b['name'])));
                 }
                 return $b['since'] <=> $a['since'];
             });
         }
         $this->template->allpastorganizers = $AllPastOrganizers;
     }
}
