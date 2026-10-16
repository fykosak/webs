<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

abstract class AbstractJSONService
{
    public function mapJsonToClass(mixed $json, bool $asArray, string $modelClassName): mixed
    {
        $mapper = new \JsonMapper();
        $mapper->bEnforceMapType = false;
        if ($asArray) {
            return $mapper->mapArray(
                array_map(function (array $datum) {
                    return self::toStd($datum);
                }, $json),
                [],
                $modelClassName
            );
        } else {
            return $mapper->map((object)$json, new $modelClassName());
        }
    }

    public static function toStd(array $datum): \stdClass
    {
        $newJson = new \stdClass();
        foreach ($datum as $key => $value) {
            if (is_array($value)) {
                $newJson->$key = self::toStd($value);
            } else {
                $newJson->$key = $value;
            }
        }
        return $newJson;
    }
}
