<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\GraphQL\TypeResolver;
use App\Service\FileUpload\FileUploadService;
use GraphQL\Type\Definition\FieldDefinition;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Messenger\HandleTrait;

class UploadDataFileMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        private TypeResolver $type,
        private FileUploadService $fileUploadService
    ) {
        parent::__construct([
            'name' => 'uploadDataFile',
            'type' => $this->type->string(),
            'args' => [
                'file' => $this->type->upload(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Upload a data file',
        ]);
    }

    private function resolve(array $args): string
    {
        /** @var UploadedFileInterface $file */
        $file = $args['file'];
        $this->fileUploadService->receive($file);

        return 'OK';
    }
}
