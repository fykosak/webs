<?php

namespace App\Model\ORM;

use App\Model\Soap\FKSDBDownloader;
use Nette\SmartObject;

abstract class AbstractSOAPService {
    use SmartObject;

    protected FKSDBDownloader $downloader;

    /**
     * ServiceEvent constructor.
     * @param FKSDBDownloader $downloader
     * @throws \Exception
     */
    public function __construct(FKSDBDownloader $downloader) {
        $this->downloader = $downloader;
    }

}