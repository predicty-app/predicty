<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FileImport;
use App\Entity\Permission;
use App\Repository\ImportRepository;
use League\Flysystem\FilesystemReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FileDownloadController extends AbstractController
{
    public function __construct(
        private ImportRepository $importRepository,
        private FilesystemReader $filesystemReader,
    ) {
    }

    #[Route('/uploads/file/{importId}', name: 'app_file_download', methods: ['GET'])]
    public function __invoke(int $importId): Response
    {
        $import = $this->importRepository->findById($importId);

        if (!$import instanceof FileImport) {
            throw new NotFoundHttpException('File not found');
        }

        $this->denyAccessUnlessGranted(Permission::DOWNLOAD_IMPORTED_FILE, $import);
        $stream = $this->filesystemReader->readStream($import->getFilename());

        return new StreamedResponse(
            callback: function () use (&$stream): void {
                fpassthru($stream);
                fclose($stream);
            },
            status: 200,
            headers: [
                'Content-Type' => $import->getMimeType(),
                'Content-Disposition' => 'attachment; filename="'.$import->getBasename().'"',
            ]
        );
    }
}
