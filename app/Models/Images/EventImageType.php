<?php

declare(strict_types=1);

namespace App\Models\Images;

enum EventImageType: string
{
    case Default = '';
    case Teams = 'teams';
    case Winners = 'winners';
    case Weekend = 'weekend';
    case Erasmus = 'erasmus';
}
