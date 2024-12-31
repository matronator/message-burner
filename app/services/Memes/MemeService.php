<?php

declare(strict_types=1);

namespace App\Services\Memes;

class MemeService
{
    public const MEMES = [
        ['url' => 'https://pd-beamliving-cd.beamliving.com/-/media/14-to-bl/2021-funny-memes-cats-1000x666px.jpg', 'type' => MemeType::JPG],
        ['url' => 'https://pd-beamliving-cd.beamliving.com/-/media/bu-to-ch/cat-meme-netflix-funny-1000x666.png', 'type' => MemeType::PNG],
        ['url' => 'https://i.giphy.com/media/rsf33kKU6WdA4/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/Qd7F5NcMFcTio/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/xT0BKiaM2VGJ553P9K/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/mqnWGg52jjBMQ/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/Uv8rZ7BsVBpX2iY2cL/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/htlPKkooHsic0/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/U7P2vnWfPkIQ8/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/Vj2fBk4JWGdxu/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/FxEwsOF1D79za/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/11ISwbgCxEzMyY/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.giphy.com/media/o0vwzuFwCGAFO/giphy.webp', 'type' => MemeType::WEBP],
        ['url' => 'https://i.imgur.com/HtDqq57.jpeg', 'type' => MemeType::JPG],
        ['url' => 'https://i.imgur.com/tjj4qt8.jpeg', 'type' => MemeType::JPG],
        ['url' => 'https://i.imgur.com/89lRvmog.jpg', 'type' => MemeType::JPG],
    ];

    public static function getMeme(): Meme
    {
        return Meme::fromArray(self::MEMES[array_rand(self::MEMES)]);
    }
}
