<?php

declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphQlController extends AbstractController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    #[Route('/graphql', name: 'app_graphql')]
    public function __invoke(Request $sfr, ServerRequestInterface $request, Schema $schema): Response
    {
        if (Request::METHOD_GET === $request->getMethod()) {
            return $this->render('graphql/index.html.twig');
        }

        $data = json_decode($request->getBody()->getContents(), true);
        $request = $request->withParsedBody($data);
        $rootValue = [];

        $config = ServerConfig::create()
            ->setRootValue($rootValue)
            ->setSchema($schema)
            ->setDebugFlag($this->getDebugFlag())
            ->setErrorsHandler(function (array $errors, callable $formatter) {
                /** @var Error[] $errors */
                foreach ($errors as $error) {
                    $this->logger->error($error->getMessage(), [
                        'exception' => $error,
                    ]);
                }

                return array_map($formatter, $errors);
            });

        return $this->json((new StandardServer($config))->executePsrRequest($request));
    }

    private function getDebugFlag(): int
    {
        $debug = DebugFlag::NONE;

        if (in_array($this->getParameter('kernel.environment'), ['dev', 'test'], true)) {
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE;
        }

        return $debug;
    }
}
