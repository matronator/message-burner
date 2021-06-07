<?php

/**
 * Copyright (c) 2021 Matronator
 *
 * This software is released under the MIT License.
 * https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace App\Libs;

use Hashids\Hashids;

class HashService
{
    public static function idToHash(int $id)
    {
        $hashids = new Hashids('messages', 8);
        return $hashids->encode($id);
    }

    public static function hashToId(string $hash)
    {
        $hashids = new Hashids('messages', 8);
        return $hashids->decode($hash)[0];
    }
}
