<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FileImport;
use App\Repository\ImportRepository;
use App\Service\User\CurrentUserService;
use League\Flysystem\FilesystemReader;
use Psr\Log\LoggerInterface;
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
        private CurrentUserService $currentUserService,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/uploads/file/{importId}', name: 'app_file_download', methods: ['GET'])]
    public function __invoke(int $importId): Response
    {
        if ($this->currentUserService->isAnonymous()) {
            $this->logger->info('Anonymous user tried to download a file', ['importId' => $importId]);

            throw new NotFoundHttpException('File not found');
        }

        $import = $this->importRepository->findById($importId);
        if (!$import instanceof FileImport) {
            $this->logger->info('User tried to download a file that does not exist', ['importId' => $importId]);

            throw new NotFoundHttpException('File not found');
        }

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
