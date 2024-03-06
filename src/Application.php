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
use Hyperf\Logger\LoggerFactory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Contract\RemoteConfig;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Exception\InvalidArgumentException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Http\HttpClientOptions;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

use function Hyperf\Support\make;

class Application implements ApplicationInterface
{
    protected Factory $factory;

    protected ConfigInterface $config;

    protected ?Auth $auth = null;

    protected ?Database $database = null;

    protected ?DynamicLinks $dynamicLinks = null;

    protected ?Firestore $firestore = null;

    protected ?Messaging $messaging = null;

    protected ?RemoteConfig $remoteConfig = null;

    protected ?Storage $storage = null;

    protected string $name = 'app';

    private LoggerFactory $loggerFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->factory = $container->get(Factory::class);
        $this->config = $container->get(ConfigInterface::class);
        $this->loggerFactory = $container->get(LoggerFactory::class);

        $project = sprintf('firebase.projects.%s', $this->name);
        $config = $this->config->get("{$project}");

        if (! $config) {
            throw new InvalidArgumentException(sprintf('Firebase project [%s] not configured.', $this->name));
        }

        // 封装配置获取
        if ($tenantId = $config['auth']['tenant_id'] ?? null) {
            $this->factory = $this->factory->withTenantId($tenantId);
        }

        if ($credentials = $config['credentials']['file'] ?? ($config['credentials'] ?? null)) {
            $this->factory = $this->factory->withServiceAccount($credentials);
        }

        if ($databaseUrl = $config['database']['url'] ?? null) {
            $this->factory = $this->factory->withDatabaseUri($databaseUrl);
        }

        if ($authVariableOverride = $config['database']['auth_variable_override'] ?? null) {
            $this->factory = $this->factory->withDatabaseAuthVariableOverride($authVariableOverride);
        }

        if ($defaultStorageBucket = $config['storage']['default_bucket'] ?? null) {
            $this->factory = $this->factory->withDefaultStorageBucket($defaultStorageBucket);
        }

        if ($cacheStore = $config['cache_store'] ?? null) {
            $cacheConfig = $config['stores'][$cacheStore];
            $cacheDriver = make($cacheConfig['driver'], ['config' => $cacheConfig]);

            if ($cacheDriver instanceof CacheInterface) {
                $cacheDriver = new Psr16Adapter($cacheDriver);
            } else {
                throw new InvalidArgumentException('The cache store must be an instance of a PSR-6 or PSR-16 cache');
            }

            $this->factory = $this->factory
                ->withVerifierCache($cacheDriver)
                ->withAuthTokenCache($cacheDriver);
        }

        if ($logChannel = $config['logging']['http_log_channel'] ?? null) {
            $this->factory = $this->factory->withHttpLogger(
                $this->loggerFactory->make($logChannel)
            );
        }

        if ($logChannel = $config['logging']['http_debug_log_channel'] ?? null) {
            $this->factory = $this->factory->withHttpDebugLogger(
                $this->loggerFactory->make($logChannel)
            );
        }

        $options = HttpClientOptions::default();

        if ($proxy = $config['http_client_options']['proxy'] ?? null) {
            $options = $options->withProxy($proxy);
        }

        if ($timeout = $config['http_client_options']['timeout'] ?? null) {
            $options = $options->withTimeOut((float) $timeout);
        }

        $this->factory = $this->factory->withHttpClientOptions($options);
    }

    public function auth(): Auth
    {
        if (! $this->auth) {
            $this->auth = $this->factory->createAuth();
        }

        return $this->auth;
    }

    public function database(): Database
    {
        if (! $this->database) {
            $this->database = $this->factory->createDatabase();
        }

        return $this->database;
    }

    public function dynamicLinks(): DynamicLinks
    {
        if (! $this->dynamicLinks) {
            $this->dynamicLinks = $this->factory->createDynamicLinksService($this->config->get("firebase.projects.{$this->name}.dynamic_links.default_domain"));
        }

        return $this->dynamicLinks;
    }

    public function firestore(): Firestore
    {
        if (! $this->firestore) {
            $this->firestore = $this->factory->createFirestore();
        }

        return $this->firestore;
    }

    public function messaging(): Messaging
    {
        if (! $this->messaging) {
            $this->messaging = $this->factory->createMessaging();
        }

        return $this->messaging;
    }

    public function remoteConfig(): RemoteConfig
    {
        if (! $this->remoteConfig) {
            $this->remoteConfig = $this->factory->createRemoteConfig();
        }

        return $this->remoteConfig;
    }

    public function storage(): Storage
    {
        if (! $this->storage) {
            $this->storage = $this->factory->createStorage();
        }

        return $this->storage;
    }
}
