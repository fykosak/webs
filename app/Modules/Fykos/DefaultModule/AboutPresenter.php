<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\Services\FileService;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @property DefaultTemplate $template
 */
final class AboutPresenter extends BasePresenter
{
    private readonly FileService $fileService;

    public function injectServiceProblem(FileService $fileService): void
    {
        $this->fileService = $fileService;
    }

    public function getYearbookLink(int $year): ?string
    {
        $yearbookPath = $this->fileService->getYearbook('fykos', $year, $this->template->lang);

        if ($yearbookPath) {
            return $this->template->getLatte()->renderToString(__DIR__ . '/templates/About/yearbookLink.' . $this->template->lang . '.latte', [
                'yearbookPath' => $yearbookPath,
                'year' => $year,
            ]);
        }

        return null;
    }

    /**
     * @throws \Throwable
     */
    public function parseOrganizers(): array
    {
        $organizers = $this->downloader->download(new OrganizersRequest(1));

        $parsedOrganizers = [];
        foreach ($organizers as $organizer) {
            if (!$organizer['showOnWeb']) {
                continue;
            }
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
                fn (array $organizer): bool => $organizer['until'] == null
                    || $organizer['until'] === $this->getCurrentYear()->year
            );
            // sort by order and last name
            setlocale(LC_COLLATE, 'cs_CZ.utf8');
            usort($currentOrganizers, function (array $a, array $b): int {
                if ($a['order'] === $b['order']) {
                    $lastNameA = explode(' ', $a['name']);
                    $lastNameA = end($lastNameA);
                    $lastNameB = explode(' ', $b['name']);
                    $lastNameB = end($lastNameB);
                    return strcoll($lastNameA, $lastNameB);
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
