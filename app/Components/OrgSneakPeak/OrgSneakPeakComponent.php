<?php

declare(strict_types=1);

namespace App\Components\OrgSneakPeak;

use Fykosak\Utils\Components\DIComponent;
use App\Models\Downloader\Downloaders\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\OrganizersRequest;
use Nette\DI\Container;

class OrgSneakPeakComponent extends DIComponent
{
    private FKSDBDownloader $downloader;

    private array $organizers;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->downloader = $container->getByType(FKSDBDownloader::class);

        $this->organizers = $this->parseOrganizers();
    }

    /**
     * @throws \Throwable
     */
    public function parseOrganizers(): array
    {
        $organizers = $this->downloader->download(new OrganizersRequest(1));

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

    public function getOrg(array $organizers, $personId): array
    {
        foreach ($organizers as $organizer) {
            if ($organizer['personId'] === $personId) {
                return $organizer;
            }
        }
        return [];
    }

    public function render($personId = null, $title = null)
    {
        $this->template->lang = $this->translator->lang;

        $this->template->organizer = $this->getOrg($this->organizers, $personId);
        if (is_null($title)) {
            $this->template->title = $this->template->organizer['name'];
        } else {
            $this->template->title = $title;
        }
        $this->template->lang = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'orgSneakPeak.latte');
    }
}
