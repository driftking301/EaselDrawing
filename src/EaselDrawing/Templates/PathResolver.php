<?php

namespace EaselDrawing\Templates;

class PathResolver
{
    /** @var string */
    private $relativePath;

    public function __construct(string $relativePath = '')
    {
        $this->relativePath = $relativePath;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function setRelativePath(string $relativePath)
    {
        $this->relativePath = '/' . trim($relativePath, '/');
    }

    public function obtainPath($location): string
    {
        if (substr($location, 0, 1) === '/') {
            // this is an absolute path
            return $location;
        }
        // complete relative path
        return $this->getRelativePath() . '/' . $location;
    }
}
