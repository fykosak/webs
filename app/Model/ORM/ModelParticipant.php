<?php

namespace App\Model\ORM;

use Fykosak\FKSDBDownloader\Downloader\AbstractSOAPModel;

class ModelParticipant extends AbstractSOAPModel {
    public int $participantId;
    public int $schoolId;
    public string $name;
    public string $email;
    public string $schoolName;
    public string $countryIso;
}
