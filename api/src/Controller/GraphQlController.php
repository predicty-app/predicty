<?php

namespace App\Controller;

use GraphQL\Error\DebugFlag;
use GraphQL\Server\ServerConfig;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphQlController extends AbstractController
{
    #[Route('/graphql', name: 'app_graphql')]
    public function __invoke(ServerRequestInterface $request): Response
    {
        if ($request->getMethod() === Request::METHOD_GET) {
            return $this->render('graphql/index.html.twig', [
                'controller_name' => 'GraphQlController',
            ]);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        $request = $request->withParsedBody($data);
        $rootValue = ['prefix' => 'You said: '];
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'echo' => [
                    'type' => Type::string(),
                    'args' => [
                        'message' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                ],
            ],
        ]);

        $schema = new Schema(['query' => $queryType]);

        $config = ServerConfig::create()
            ->setRootValue($rootValue)
            ->setSchema($schema)
            ->setDebugFlag(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE | DebugFlag::RETHROW_UNSAFE_EXCEPTIONS | DebugFlag::RETHROW_INTERNAL_EXCEPTIONS)
        ;

        $server = new StandardServer($config);
        $result = $server->executePsrRequest($request);

        return $this->json($result);
    }
}
