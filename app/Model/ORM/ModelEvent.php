<?php

namespace App\Model\ORM;

use DateTimeInterface;
use Fykosak\FKSDBDownloader\Downloader\AbstractSOAPModel;

class ModelEvent extends AbstractSOAPModel {

    public int $eventId;
    public string $name;
    public int $eventYear;
    public DateTimeInterface $begin;
    public DateTimeInterface $end;
    public DateTimeInterface $registrationBegin;
    public DateTimeInterface $registrationEnd;
}
