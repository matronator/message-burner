<?php

declare(strict_types=1);

namespace App\Services;

class ExpiryService
{
    public string $hash;
    public string $confirm;

    public const EXPIRATION_OPTIONS = [
        'day' => 'general.messageForm.expirations.day',
        'twoDays' => 'general.messageForm.expirations.twoDays',
        'threeDays' => 'general.messageForm.expirations.threeDays',
        'week' => 'general.messageForm.expirations.week',
        'twoWeeks' => 'general.messageForm.expirations.twoWeeks',
        'month' => 'general.messageForm.expirations.month',
    ];

    public const EXPIRATION_DATES = [
        'day' => '+1 day',
        'twoDays' => '+2 days',
        'threeDays' => '+3 days',
        'week' => '+1 week',
        'twoWeeks' => '+2 weeks',
        'month' => '+1 month',
    ];

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
