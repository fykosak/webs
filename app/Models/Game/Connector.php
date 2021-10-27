<?php

declare(strict_types=1);

namespace App\Models\Game;

use Nette\Application\BadRequestException;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\SmartObject;

class Connector
{
    use SmartObject;

    private ?string $url;
    private Storage $storage;

    public function __construct(?string $url, Storage $storage)
    {
        $this->storage = $storage;
        $this->url = $url;
    }

    /**
     * @throws \Throwable
     */
    public function getResults(): array
    {
        if (!isset($this->url)) {
            throw new BadRequestException('Game URL is not set');
        }
        return $this->getCache()->load('results', function (?array &$dependencies): array {
            $data = json_decode(file_get_contents($this->url), true);
            $dependencies[Cache::EXPIRE] = ($data['refreshDelay'] / 1000) . ' seconds';
            return $data;
        });
    }

    private function getCache(): Cache
    {
        static $cache;
        if (!isset($cache)) {
            $cache = new Cache($this->storage, self::class);
        }
        return $cache;
    }
}
