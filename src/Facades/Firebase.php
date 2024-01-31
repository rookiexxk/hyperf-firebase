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

namespace Fcorz\Hyperf\Firebase\Facades;

use Fcorz\Hyperf\Firebase\Application;
use Fcorz\Hyperf\Firebase\ApplicationFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;

/**
 * @method static string getDefaultProject()
 * @method static void setDefaultProject(string $name)
 * @method static \Kreait\Firebase\Contract\Auth auth()
 * @method static \Kreait\Firebase\Contract\Database database()
 * @method static \Kreait\Firebase\Contract\DynamicLinks dynamicLinks()
 * @method static \Kreait\Firebase\Contract\Firestore firestore()
 * @method static \Kreait\Firebase\Contract\Messaging messaging()
 * @method static \Kreait\Firebase\Contract\RemoteConfig remoteConfig()
 * @method static \Kreait\Firebase\Contract\Storage storage()
 *
 * @see ApplicationFactory
 * @see Application
 */
class Firebase
{
    public static function __callStatic($name, $arguments)
    {
        return self::project()->{$name}(...$arguments);
    }

    public static function project(?string $name = null): Application
    {
        /** @var ContainerInterface $container */
        $container = ApplicationContext::getContainer();
        /** @var ApplicationFactory $factory */
        $factory = $container->get(ApplicationFactory::class);

        return $factory->get($name);
    }
}
