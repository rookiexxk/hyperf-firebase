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

namespace Fcorz\Hyperf\Firebase\Facades;

use Fcorz\Hyperf\Firebase\ApplicationInterface;
use Fcorz\Hyperf\Firebase\ApplicationManager;
use Hyperf\Utils\ApplicationContext;

/**
 * @method static \Kreait\Firebase\Contract\Auth auth()
 * @method static \Kreait\Firebase\Contract\Database database()
 * @method static \Kreait\Firebase\Contract\DynamicLinks dynamicLinks()
 * @method static \Kreait\Firebase\Contract\Firestore firestore()
 * @method static \Kreait\Firebase\Contract\Messaging messaging()
 * @method static \Kreait\Firebase\Contract\RemoteConfig remoteConfig()
 * @method static \Kreait\Firebase\Contract\Storage storage()
 *
 * @see ApplicationManager
 * @see ApplicationInterface
 */
class Firebase
{
    public static function __callStatic($name, $arguments)
    {
        return self::project()->{$name}(...$arguments);
    }

    public static function project(?string $name = null): ApplicationInterface
    {
        $container = ApplicationContext::getContainer();
        $manager = $container->get(ApplicationManager::class);

        return $manager->get($name);
    }
}
