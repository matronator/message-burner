<?php

/**
 * Copyright (c) 2021 Matronator
 *
 * This software is released under the MIT License.
 * https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace App\Services;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\File;
use Defuse\Crypto\Key;

class EncryptionService
{
    public string $algo;
    public string $password;
    public string $passwordHash;
    public Key $key;

    public function __construct($params)
    {
        $params = (object) $params;
        $this->algo = $params->algo ?? constant('PASSWORD_BCRYPT');
        $this->passwordHash = $params->passwordHash ?? '';
        $this->password = $params->password;
        $this->key = Key::loadFromAsciiSafeString($params->key);
    }

    /**
     * Encrypts a text message or a file using the Key from config
     *
     * @return string Encrypted message or file
     * @param string $message Message or file to encrypt
     * @param boolean $isFile (default: false) True if encrypting a file
     */
    public function encrypt(string $message, bool $isFile = false): string
    {
        return Crypto::encrypt($message, $this->key, $isFile);
    }

    /**
     * Decrypts a text message or a file using the Key from config
     *
     * @return string Decrypted message or file
     * @param string $message Message or file to decrypt
     * @param boolean $isFile (default: false) True if decrypting a file
     */
    public function decrypt(string $message, bool $isFile = false): string
    {
        return Crypto::decrypt($message, $this->key, $isFile);
    }

    /**
     * Encrypts a text message or a file using password instead of a Key. Uses password from config if none is provided.
     *
     * @return string Encrypted message or file
     * @param string $message Message or file to encrypt
     * @param string|null $password If null, password from config will be used.
     * @param boolean $isFile (default: false) True if encrypting a file
     */
    public function encryptWithPassword(string $message, ?string $password = null, bool $isFile = false): string
    {
        return Crypto::encryptWithPassword($message, $password ?? $this->password, $isFile);
    }

    /**
     * Decrypts a text message or a file using password instead of a Key. Uses password from config if none is provided.
     *
     * @return string Decrypted message or file
     * @param string $message Message or file to decrypt
     * @param string|null $password If null, password from config will be used.
     * @param boolean $isFile (default: false) True if decrypting a file
     */
    public function decryptWithPassword(string $message, ?string $password = null, bool $isFile = false): string
    {
        return Crypto::decryptWithPassword($message, $password ?? $this->password, $isFile);
    }

    public function encryptFile(string $source, string $destination)
    {
        $encrypted = File::encryptFile($source, $destination, $this->key);
        unlink($source);
        return $encrypted;
    }

    public function decryptFile(string $source, string $destination)
    {
        $decrypted = File::decryptFile($source, $destination, $this->key);
        unlink($source);
        return $decrypted;
    }

    public function encryptFileWithPassword(string $source, string $destination, ?string $password = null)
    {
        $encrypted = File::encryptFileWithPassword($source, $destination, $password ?? $this->password);
        unlink($source);
        return $encrypted;
    }

    public function decryptFileWithPassword(string $source, string $destination, ?string $password = null)
    {
        $decrypted = File::decryptFileWithPassword($source, $destination, $password ?? $this->password);
        unlink($source);
        return $decrypted;
    }
}
