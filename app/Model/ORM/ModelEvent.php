<?php

namespace App\Model\ORM;

use DOMNode;
use Nette\SmartObject;
use Tracy\Debugger;

class ModelEvent {
    use SmartObject;

    public int $eventId;
    public string $name;
    public \DateTimeInterface $begin;
    public \DateTimeInterface $end;
    public \DateTimeInterface $registrationBegin;
    public \DateTimeInterface $registrationEnd;
}