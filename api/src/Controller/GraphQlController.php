<?php

declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\Schema;
use GraphQL\Error\DebugFlag;
use GraphQL\Server\Helper;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphQlController extends AbstractController
{
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
        ;

        return $this->json((new StandardServer($config))->executePsrRequest($request));
    }

    private function getDebugFlag(): int
    {
        $debug = DebugFlag::NONE;

        if($this->getParameter('kernel.environment') === 'dev') {
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE;
        }

        return $debug;
    }
}
