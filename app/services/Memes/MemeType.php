<?php

declare(strict_types=1);

namespace App\Services\Memes;

enum MemeType: string
{
    case JPG = 'jpg';
    case PNG = 'png';
    case GIF = 'gif';
    case WEBP = 'webp';
}
