<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\FileImportType;
use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ScheduleFileImport;
use App\Service\FileUpload\FileUploadService;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadDataFileMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        private TypeRegistry $type,
        private FileUploadService $fileUploadService,
        private CurrentUser $currentUser,
        private MessageBusInterface $commandBus
    ) {
        parent::__construct([
            'name' => 'uploadDataFile',
            'type' => $this->type->string(),
            'args' => [
                'file' => $this->type->upload(),
                'campaignName' => [
                    'type' => $this->type->string(),
                    'description' => 'You can specify campaign name if imported file is of simplified format. Otherwise it will be skipped.',
                ],
                'type' => $this->type->nonNull($this->type->fileImportType()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Upload a data file',
        ]);
    }

    private function resolve(array $args): string
    {
        /** @var FileImportType $fileImportType */
        $fileImportType = $args['type'];
        $filename = $this->fileUploadService->receive($args['file']);

        $this->commandBus->dispatch(
            new ScheduleFileImport(
                userId: $this->currentUser->getId(),
                dataProvider: $fileImportType->getDataProvider(),
                fileImportType: $fileImportType,
                filename: $filename,
                metadata: ['campaignName' => $args['campaignName'] ?? null]
            )
        );

        return 'OK';
    }
}
