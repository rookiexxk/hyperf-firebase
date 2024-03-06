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

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

use function Hyperf\Support\make;

class ApplicationManager
{
    /**
     * @var ApplicationInterface[]
     */
    protected array $applications = [];

    private ConfigInterface $config;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class);

        foreach ($this->config->get('firebase.projects', []) as $name => $item) {
            $this->applications[$name] = make(ApplicationProxy::class, ['name' => $name]);
        }
    }

    public function get(?string $name = null): ApplicationInterface
    {
        $name ??= $this->config->get('firebase.default', 'app');

        if (! isset($this->applications[$name])) {
            throw new \RuntimeException(sprintf('The application "%s" is not exists.', $name));
        }

        return $this->applications[$name];
    }
}
