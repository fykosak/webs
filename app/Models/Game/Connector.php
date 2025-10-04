<?php

declare(strict_types=1);

namespace App\Models\Game;

use Nette\Application\BadRequestException;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

class Connector
{
    public function __construct(
        private readonly ?string $url,
        private readonly ?string $httpAuthUser,
        private readonly ?string $httpAuthPassword,
        private readonly Storage $storage
    ) {
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
            $context = null;
            if ($this->httpAuthUser) {
                $auth = base64_encode($this->httpAuthUser . ':' . $this->httpAuthPassword);
                $context = stream_context_create([
                    'http' => [
                        'header' => "Authorization: Basic $auth"
                    ]
                ]);
            }

            $data = json_decode(file_get_contents($this->url, false, $context), true);
            if ($data['times']['toEnd'] > 0) {
                $dependencies[Cache::EXPIRE] = min($data['refreshDelay'] / 1000, $data['times']['toEnd']) . ' seconds';
            } else {
                $dependencies[Cache::EXPIRE] = ($data['refreshDelay'] / 1000) . ' seconds';
            }
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
