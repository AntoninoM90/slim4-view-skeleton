<?php

declare(strict_types=1);

namespace App\Application\Actions\HomePage;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

final class HomePageAction extends Action
{
    /**
     * {@inheritdoc}
     *
     * Home page action.
     *
     * @return Response
     */
    protected function action(): Response
    {
        // Logging
        $this->logger->info('The home page was viewed.');

        // Return the template render
        return $this->render('HomePage/homepage.html.twig', [
            'name' => 'World'
        ]);
    }
}
