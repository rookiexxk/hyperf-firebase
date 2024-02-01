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

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Contract\RemoteConfig;
use Kreait\Firebase\Contract\Storage;

interface ApplicationInterface
{
    public function auth(): Auth;

    public function database(): Database;

    public function dynamicLinks(): DynamicLinks;

    public function firestore(): Firestore;

    public function messaging(): Messaging;

    public function remoteConfig(): RemoteConfig;

    public function storage(): Storage;
}
