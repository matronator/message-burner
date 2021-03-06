<?php

/**
 * Copyright (c) 2021 Matronator
 *
 * This software is released under the MIT License.
 * https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace App\Services;

use Hashids\Hashids;
use Nette\Security\Passwords;

class HashService
{
    public const HASH_LENGTH = 16;

    /**
     * Encode an integer ID to a hash
     *
     * @return string The resulting hash string
     * @param integer $id ID to be hashed
     * @param string $namespace Namespace to use as the salt. Different namespaces produce different hashes for the same ID
     */
    public static function idToHash(int $id, string $namespace = 'messages'): string
    {
        $hashids = new Hashids($namespace, self::HASH_LENGTH);
        return $hashids->encode($id);
    }

    public static function hashToId(string $hash, string $namespace = 'messages'): int
    {
        $hashids = new Hashids($namespace, self::HASH_LENGTH);
        return $hashids->decode($hash)[0] ?? -1;
    }

    public static function hashPassword(string $password): string
    {
        $security = new Passwords;
        return $security->hash($password);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        $security = new Passwords;
        return $security->verify($password, $hash);
    }
}
