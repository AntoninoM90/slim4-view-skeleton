<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Set dependencies of the application.
 *
 * @param ContainerBuilder $containerBuilder The container builder
 *
 * @return void
 */
return function (
    ContainerBuilder $containerBuilder
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
    ]);
};
