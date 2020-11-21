<?php

namespace App\Model\ORM;

class ModelParticipant extends AbstractSOAPModel {
    public int $participantId;
    public int $schoolId;
    public string $name;
    public string $email;
    public string $schoolName;
    public string $countryIso;
}