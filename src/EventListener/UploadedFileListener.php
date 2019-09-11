<?php

/*
 * This file is part of the tradable.
 *
 * @author siganushka <siganushka@gmail.com>
 */

namespace App\EventListener;

use App\Event\FilterUploadedFileEvent;
use App\Events;
use App\Util\FileUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadedFileListener implements EventSubscriberInterface
{
    private $fileUtil;

    public function __construct(FileUtil $fileUtil)
    {
        $this->fileUtil = $fileUtil;
    }

    public function onUploadedFile(FilterUploadedFileEvent $event)
    {
        $file = $event->getFile();

        $event->setData([
            'url' => $this->fileUtil->getAccessableUrl($file),
            'name' => $file->getFilename(),
            'path' => $this->fileUtil->getRelativePath($file),
            'size' => $file->getSize(),
            'size_formatted' => $this->fileUtil->getFormattedSize($file),
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
