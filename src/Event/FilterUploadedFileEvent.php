<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilterUploadedFileEvent extends Event
{
    private $data = [];

    private $file;
    private $uploadedFile;

    public function __construct(File $file, UploadedFile $uploadedFile)
    {
        $this->file = $file;
        $this->uploadedFile = $uploadedFile;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
