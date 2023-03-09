<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\FileImportType;
use App\GraphQL\TypeResolver;
use App\Message\Command\ImportDataFromFile;
use App\Service\FileUpload\FileUploadService;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\PhpEnumType;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadDataFileMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        private TypeResolver $type,
        private FileUploadService $fileUploadService,
        private CurrentUserService $currentUserService,
        private MessageBusInterface $commandBus
    ) {
        parent::__construct([
            'name' => 'uploadDataFile',
            'type' => $this->type->string(),
            'args' => [
                'file' => $this->type->upload(),
                'type' => $this->type->nonNull(new PhpEnumType(FileImportType::class)),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Upload a data file',
        ]);
    }

    private function resolve(array $args): string
    {
        $filename = $this->fileUploadService->receive($args['file']);
        $this->commandBus->dispatch(
            new ImportDataFromFile($this->currentUserService->getId(), $args['type'], $filename)
        );

        return 'OK';
    }
}
