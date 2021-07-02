<?php

declare(strict_types=1);

namespace App\Services;

class ExpiryService
{
    public string $hash;
    public string $confirm;

    public function __construct($params)
    {
        $params = (object) $params;
        $this->hash = $params->hash;
        $this->confirm = $params->confirm;
    }

    public function verifyExpiration(string $hash = '', string $confirm = ''): bool
    {
        return ($hash === $this->hash && $confirm === $this->confirm);
    }
}
