<?php

namespace App\Model;

use Nette\SmartObject;

class ModelEvent {
    use SmartObject;

    public int $eventId;
    public string $name;
    public \DateTimeInterface $begin;
    public \DateTimeInterface $end;
    public \DateTimeInterface $registrationBegin;
    public \DateTimeInterface $registrationEnd;

    /**
     * @param \DOMNode $node
     * @return static
     * @throws \Exception
     */
    public static function createFromXMLNode(\DOMNode $node): self {
        $model = new static();
        /** @var \DOMNode $value */
        foreach ($node->childNodes as $value) {
            switch ($node->nodeName) {
                case 'eventId':
                    $model->eventId = (int)$value->nodeValue;
                    break;
                case 'name':
                    $model->name = (string)$value->nodeValue;
                    break;
                case 'begin':
                    $model->begin = new \DateTime($value->nodeValue);
                    break;
                case 'end':
                    $model->end = new \DateTime($value->nodeValue);
                    break;
                case 'registrationBegin':
                    $model->registrationBegin = new \DateTime($value->nodeValue);
                    break;
                case 'registrationEnd':
                    $model->registrationEnd = new \DateTime($value->nodeValue);
                    break;
                default:
                    break;
            }
        }
        return $model;
    }
}