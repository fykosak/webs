<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use DOMDocument;

class CampsPresenter extends BasePresenter
{
    public function renderDefault()
    {
        // Fetch the webpage
        $html = file_get_contents('https://fykos.cz/akce/soustredeni/start');

        // Create a new DOM document
        $doc = new DOMDocument();

        // Suppress errors due to ill-formed HTML
        libxml_use_internal_errors(true);

        // Load the HTML
        $doc->loadHTML($html);

        // Clear the errors
        libxml_clear_errors();

        // Extract the required part
        $content = $doc->getElementById('dokuwiki__content');

        // Display the extracted part
        $htmlContent = $doc->saveHTML($content);

        $this->template->oldContent = $htmlContent;
    }
}
