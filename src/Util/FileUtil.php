<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Util;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RequestStack;

class FileUtil
{
    private $requestStack;
    private $publicDir;

    public function __construct(RequestStack $requestStack, string $publicDir)
    {
        $this->requestStack = $requestStack;
        $this->publicDir = $publicDir;
    }

    public function getAccessableUrl(File $file)
    {
        $request = $this->requestStack->getCurrentRequest();
        $relativePath = $this->getRelativePath($file);

        return $request->getSchemeAndHttpHost().'/'.ltrim($relativePath, '/');
    }

    public function getRelativePath(File $file): ?string
    {
        $path = str_replace('\\', '/', $file->getRealPath());
        if (false !== strpos($path, $this->publicDir)) {
            $path = substr($path, \strlen($this->publicDir));
        }

        return $path;
    }

    public function getFormattedSize(File $file)
    {
        $base = log($file->getSize(), 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), 2).$suffixes[floor($base)];
    }
}
