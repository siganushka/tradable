<?php

namespace App\EventSubscriber;

use App\Event\UploadedFileEvent;
use App\Utils\FileUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UploadedFileSubscriber implements EventSubscriberInterface
{
    private $fileUtils;

    public function __construct(FileUtils $fileUtils)
    {
        $this->fileUtils = $fileUtils;
    }

    public function onUploadedFileEvent(UploadedFileEvent $event)
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
            UploadedFileEvent::class => 'onUploadedFileEvent',
        ];
    }
}
