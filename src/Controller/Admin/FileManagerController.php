<?php

namespace App\Controller\Admin;

use App\Event\UploadedFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileManagerController extends AbstractController
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/files", name="admin_file", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $data = [
            // 'img1' => 'http://tradable.example.com/upload/product/6785e8f16256a34b.jpeg',
            // 'img2' => 'http://tradable.example.com/upload/product/6785e8f16256a34b.jpeg',
        ];

        $form = $this->createFormBuilder($data)
            ->add('img1', 'App\Form\Type\ChooseImageType', ['channel' => 'product'])
            ->add('img2', 'App\Form\Type\ChooseImageType', ['channel' => 'product'])
            ->add('text', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('save', 'Symfony\Component\Form\Extension\Core\Type\SubmitType')
            ->getForm();

        // dd($form->createView());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('admin/file_manager/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/files/upload/{channel}", name="admin_file_upload", methods="POST")
     */
    public function upload(Request $request, string $publicDir, string $channel)
    {
        sleep(1);

        $uploadedFile = $request->files->get('filedata');
        if (!$uploadedFile instanceof UploadedFile) {
            return $this->json(['message' => 'No file was found.'], 400);
        }

        $fileName = uniqid(rand(100, 999)).'.'.$uploadedFile->guessExtension();

        try {
            $file = $uploadedFile->move("{$publicDir}/upload/{$channel}", $fileName);
        } catch (FileException $e) {
            return $this->json(['message' => $e->getMessage()], 400);
        }

        $event = new UploadedFileEvent($file, $uploadedFile);
        $this->dispatcher->dispatch($event);

        if (!$event->isPropagationStopped()) {
            return $this->json(['message' => 'No listeners was found.'], 400);
        }

        return $this->json($event->getData());
    }
}
