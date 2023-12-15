<?php

declare(strict_types=1);

namespace App\Services\Memes;

class Meme
{
    public function __construct(public string $url, public MemeType $type)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self($data['url'], $data['type']);
    }
}
