<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;
use Tracy\Debugger;

final class AboutPresenter extends BasePresenter
{
    private FKSDBDownloader $downloader;


    //private int $currentCalendarYear = 2023; // TODO: get from db
    private int $currentFYKOSYear = 37; // TODO: get from db
    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function parseOrganizers(): array
    {
        $response = $this->downloader->download(new OrganizersRequest(1));
        $organizers = json_decode($response, true);

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
                $parsedOrganizers[] = $parsedOrganizer;
            }
            return $parsedOrganizers;
        }
        return [];
    }

    public function isCurrentOrganizer(array $organizer): bool
    {
        if ($organizer['until'] == null or $organizer['until'] == $this->currentFYKOSYear) {
            return true;
        }
        return false;
    }

    public function renderDefault(): void
    {
        $this->template->currentFYKOSYear = $this->currentFYKOSYear;
    }

    public function renderOrganizers(): void
    {
        $allOrganizers = $this->parseOrganizers();
        $currentOrganizers = [];

        if ($allOrganizers !== []) {
            $currentOrganizers = array_filter($allOrganizers, array($this, "isCurrentOrganizer"));

            // sort by order
            usort($currentOrganizers, function ($a, $b) {
                if ($a['order'] === $b['order']) {
                    $last_name_a = explode(' ', $a['name']);
                    $last_name_a = end($last_name_a);
                    $last_name_b = explode(' ', $b['name']);
                    $last_name_b = end($last_name_b);
                    return $last_name_a <=> $last_name_b;
                }
                return $b['order'] <=> $a['order'];
            });
        }
        $this->template->currentOrganizers = $currentOrganizers;
    }

    public function renderAllPastOrganizers(): void
    {
        $allOrganizers = $this->parseOrganizers();

        if ($allOrganizers !== []) {
            // sort by order
            usort($allOrganizers, function ($a, $b) {
                if ($a['since'] === $b['since']) {
                    $last_name_a = explode(' ', $a['name']);
                    $last_name_a = end($last_name_a);
                    $last_name_b = explode(' ', $b['name']);
                    $last_name_b = end($last_name_b);
                    return $last_name_a <=> $last_name_b;
                }
                return $a['since'] <=> $b['since'];
            });
        }
        $this->template->allOrganizers = $allOrganizers;
    }

    public function renderHistory(): void
    {
        $this->template->currentFYKOSYear = $this->currentFYKOSYear;
    }
}
