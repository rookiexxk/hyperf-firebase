<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-firebase.
 *
 * @link     https://github.com/fcorz/hyperf-firebase
 * @document https://github.com/fcorz/hyperf-firebase/blob/main/README.md
 * @contact  fengchenorz@gmail.com
 * @license  https://github.com/fcorz/hyperf-firebase/blob/main/LICENSE
 */

namespace Fcorz\Hyperf\Firebase;

use GuzzleHttp\Client;

class ConfigProvider
{
    public function __invoke(): array
    {
        defined('BASE_PATH') or define('BASE_PATH', '');

        return [
            'annotations' => [
                'scan' => [
                    'class_map' => [
                        Client::class => __DIR__ . '/../classmap/GuzzleHttp/Client.php',
                    ],
                ],
            ],
            'dependencies' => [
                ApplicationInterface::class => fn($container) => $container->get(ApplicationManager::class)->get(),
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for Hyperf-Firebase.',
                    'source' => __DIR__ . '/../publish/firebase.php',
                    'destination' => BASE_PATH . '/config/autoload/firebase.php',
                ],
            ],
        ];
    }
}
