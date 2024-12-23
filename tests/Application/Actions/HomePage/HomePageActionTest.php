<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use Tests\TestCase;

class HomePageActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var ContainerInterface $container */
        $container = $app->getContainer();

        /** @var Twig $twig */
        $twig = $container->get(Twig::class);

        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $render = $twig->render(
            new Response(),
            'HomePage/homepage.html.twig',
            [ 'name' => 'World' ]
        );

        $expectedPayload = $render->getBody()->__toString();

        $this->assertEquals($expectedPayload, $payload);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
