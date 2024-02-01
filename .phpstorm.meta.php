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

namespace PHPSTORM_META;

use Hyperf\Context\Context;
use Psr\Container\ContainerInterface;

// Reflect
override(ContainerInterface::get(0), map(['' => '@']));
override(Context::get(0), map(['' => '@']));
override(\make(0), map(['' => '@']));
override(\di(0), map(['' => '@']));
override(\app(0), map(['' => '@']));
override(\optional(0), type(0));
override(\tap(0), type(0));
