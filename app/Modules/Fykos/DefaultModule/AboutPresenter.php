<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;

final class AboutPresenter extends BasePresenter
{
    private FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
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

    /**
     * @throws \Throwable
     */
    public function renderOrganizers(): void
    {
        $allOrganizers = $this->parseOrganizers();
        $currentOrganizers = [];

        if ($allOrganizers !== []) {
            $currentOrganizers = array_filter(
                $allOrganizers,
                fn(array $organizer): bool => $organizer['until'] == null
                    || $organizer['until'] == self::CURRENT_YEAR
            );

            // sort by order
            usort($currentOrganizers, function (array $a, array $b): int {
                if ($a['order'] === $b['order']) {
                    $lastNameA = explode(' ', $a['name']);
                    $lastNameA = end($lastNameA);
                    $lastNameB = explode(' ', $b['name']);
                    $lastNameB = end($lastNameB);
                    return $lastNameA <=> $lastNameB;
                }
                return $b['order'] <=> $a['order'];
            });
        }
        $this->template->currentOrganizers = $currentOrganizers;
    }

    /**
     * @throws \Throwable
     */
    public function renderAllPastOrganizers(): void
    {
        $allOrganizers = $this->parseOrganizers();

        if ($allOrganizers !== []) {
            // sort by order
            usort($allOrganizers, function (array $a, array $b): int {
                if ($a['since'] === $b['since']) {
                    $lastNameA = explode(' ', $a['name']);
                    $lastNameA = end($lastNameA);
                    $lastNameB = explode(' ', $b['name']);
                    $lastNameB = end($lastNameB);
                    return $lastNameA <=> $lastNameB;
                }
                return $a['since'] <=> $b['since'];
            });
        }
        $this->template->allOrganizers = $allOrganizers;
    }
}
