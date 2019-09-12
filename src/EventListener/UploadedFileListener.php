<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\EventListener;

use App\Events;
use App\Util\FileUtils;
use App\Event\UploadedFileEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadedFileListener implements EventSubscriberInterface
{
    private $fileUtils;

    public function __construct(FileUtils $fileUtils)
    {
        $this->fileUtils = $fileUtils;
    }

    public function onUploadedFile(UploadedFileEvent $event)
    {
        $file = $event->getFile();

        $event->setData([
            'url' => $this->fileUtils->getAbsoluteUrl($file),
            'name' => $file->getFilename(),
            'path' => $this->fileUtils->getRelativePath($file),
            'size' => $file->getSize(),
            'size_formatted' => $this->fileUtils->getFormattedSize($file),
            'extension' => $file->getExtension(),
        ])->stopPropagation();
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::UPLOADED_FILE => 'onUploadedFile',
        ];
    }
}
