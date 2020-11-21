<?php

namespace App\Model\ORM;

use DOMNode;
use Exception;
use Nette\SmartObject;
use Nette\Utils\Reflection;

abstract class AbstractSOAPModel {
    use SmartObject;

    /**
     * @param DOMNode $node
     * @return static
     * @throws Exception
     */
    public static function createFromXMLNode(DOMNode $node): self {
        $model = new static();
        /** @var DOMNode $value */
        foreach ($node->childNodes as $value) {
            if (static::handleAccessProperty($value, $model)) {
                continue;
            }
            try {
                $type = Reflection::getPropertyType(new \ReflectionProperty(static::class, $value->nodeName));
                switch ($type) {
                    case 'string';
                        $model->{$value->nodeName} = (string)$value->nodeValue;
                        break;
                    case 'int';
                        $model->{$value->nodeName} = (int)$value->nodeValue;
                        break;
                    case 'DateTimeInterface';
                        $model->{$value->nodeName} = new \DateTime($value->nodeValue);
                        break;
                    case 'bool';
                        $model->{$value->nodeName} = (bool)$value->nodeValue;
                        break;
                    default:
                        break;
                }
            } catch (\ReflectionException$exception) {
            }
        }
        return $model;
    }

    static protected function handleAccessProperty(DOMNode $node, self $model): bool {
        return false;
    }
}