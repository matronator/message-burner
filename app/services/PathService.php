<?php

declare(strict_types=1);

namespace App\Services;

class PathService
{
    public function __construct(public string $rootPath, public string $appPath, public string $wwwPath)
    {
    }

    public function getWwwDir(): string
    {
        return $this->wwwPath;
    }

    public function getRootDir(): string
    {
        return $this->rootPath;
    }

    public function getAppDir(): string
    {
        return $this->appPath;
    }
}
