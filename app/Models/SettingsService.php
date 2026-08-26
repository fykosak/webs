<?php

declare(strict_types=1);

namespace App\Models;

/** Settings service
 *
 * Dummy service used as a wrapper for neon settings specified in 'parameters',
 * that are need in the application.
 */
readonly class SettingsService
{
    public function __construct(
        public ?array $domains
    ) {
    }
}
