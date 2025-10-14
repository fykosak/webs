<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\ContestsRequest;
use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;

class ExorgsPresenter extends BasePresenter
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
         $exOrganizers = [];


         if ($allOrganizers !== []) {
            $exOrganizers = array_filter(
                $allOrganizers,
                fn(array $organizer): bool => $organizer['state'] == 'inactive'
                && $organizer['showOnWeb']
            );
            usort($exOrganizers, function (array $a, array $b): int {
                if ($a['order'] === $b['order']) {
                    return implode(' ', array_reverse(explode(' ', $a['name']))) <=> implode(' ', array_reverse(explode(' ', $b['name'])));
                }
                return $b['order'] <=> $a['order'];
            });
        }
             $this->template->exorganizers = $exOrganizers;
    }
        }

