<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

/**
 * Set dependencies of the application.
 *
 * @param ContainerBuilder $containerBuilder The container builder
 *
 * @return void
 */
return function (
    ContainerBuilder $containerBuilder,
    RequestInterface $request
) {
    $containerBuilder->addDefinitions([
        // Logger
        LoggerInterface::class => function (ContainerInterface $c): LoggerInterface {
            $settings = $c->get(SettingsInterface::class);

            // Get Logger settings
            $loggerSettings = $settings->get('logger');

            // Initialize the Logger
            $logger = new Logger($loggerSettings['name']);

            // Push Uid processor
            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            // Push stream handler
            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            // Return the logger
            return $logger;
        },

        // Twig
        Twig::class => function (
            ContainerInterface $container
        ) use($request): Twig {
            /** @var SettingsInterface $settings */
            $settings = $container->get(SettingsInterface::class);

            /** @var array $twigSettings */
            $twigSettings = $settings->get('twig');

            return Twig::create(
                $twigSettings['templates'], 
                [ 'cache' => $twigSettings['cache'] ]
            );
        },
    ]);
};
