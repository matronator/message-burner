<?php

declare(strict_types=1);

namespace App\Services;

class PathService
{
    public string $rootPath;
    public string $appPath;
    public string $wwwPath;

    public function __construct(string $rootPath, string $appPath, string $wwwPath)
    {
        $this->rootPath = $rootPath;
        $this->appPath = $appPath;
        $this->wwwPath = $wwwPath;
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
