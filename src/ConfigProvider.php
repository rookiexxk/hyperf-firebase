<?php

declare(strict_types=1);
/**
 * This file is part of config-anyway.
 *
 * @link     https://github.com/fcorz/hyperf-firebase
 * @document https://github.com/fcorz/hyperf-firebase/blob/main/README.md
 * @contact  fengchenorz@gmail.com
 * @license  https://github.com/fcorz/hyperf-firebase/blob/main/LICENSE
 */

namespace Fcorz\Hyperf\Firebase;

class ConfigProvider
{
    public function __invoke(): array
    {
        defined('BASE_PATH') or define('BASE_PATH', '');

        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for Tinker.',
                    'source' => __DIR__ . '/../publish/firebase.php',
                    'destination' => BASE_PATH . '/config/autoload/firebase.php',
                ],
            ],
        ];
    }
}
