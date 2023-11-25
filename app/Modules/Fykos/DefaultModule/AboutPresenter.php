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
        $organizers = json_decode($response, true);

        // parse orgs
        if ($organizers !== null) {
            $parsedOrganizers = [];
            foreach ($organizers as $organizer) {
                $parsedOrganizer = [
                    'name' => $organizer['name'],
                    'personId' => $organizer['personId'],
                    'email' => $organizer['email'],
                    'academicDegreePrefix' => $organizer['academicDegreePrefix'],
                    'academicDegreeSuffix' => $organizer['academicDegreeSuffix'],
                    'career' => $organizer['career'],
                    'contribution' => $organizer['contribution'],
                    'order' => $organizer['order'],
                    'role' => $organizer['role'],
                    'since' => $organizer['since'],
                    'until' => $organizer['until'],
                    'texSignature' => $organizer['texSignature'],
                    'domainAlias' => $organizer['domainAlias'],
                ];
                
                // only add current organizers
                $current_year = 37;
                if ($parsedOrganizer['until'] == null or $parsedOrganizer['until'] == $current_year) {
                    $parsedOrganizers[] = $parsedOrganizer;    
                }
                
            }

            // sort by order
            usort($parsedOrganizers, function ($a, $b) {
                if ($a['order'] === $b['order']) {
                    $last_name_a = explode(' ', $a['name']);
                    $last_name_a = end($last_name_a);
                    $last_name_b = explode(' ', $b['name']);
                    $last_name_b = end($last_name_b);
                    return $last_name_a <=> $last_name_b;
                }
                return $b['order'] <=> $a['order'];
            });

            $this->template->organizers = $parsedOrganizers;
        }
    }
}
