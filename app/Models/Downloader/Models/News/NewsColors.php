<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\News;

enum NewsColors: string
{
    case Fykos = 'fykos';
    case DSEF = 'dsef';
    case FOF = 'fof';
    case FOL = 'fol';
    case Naboj = 'naboj';
    case Vyfuk = 'vyfuk';
    case NabojJunior = 'naboj_junior';

    public function label(): string
    {
        return match ($this) {
            self::Fykos => 'Fykos',
            NewsColors::DSEF => 'DSEF',
            NewsColors::FOF => 'FOF',
            NewsColors::FOL => 'FOL',
            NewsColors::Naboj => 'Náboj',
            NewsColors::Vyfuk => 'Výfuk',
            NewsColors::NabojJunior => 'Náboj Junior'
        };
    }
}
