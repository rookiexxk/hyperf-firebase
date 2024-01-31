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

use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Contract\RemoteConfig;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Http\HttpClientOptions;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

class Application
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

    protected string $name;

    private LoggerFactory $logger;

    public function __construct(ContainerInterface $container, string $name = 'app')
    {
        $this->factory = $container->get(Factory::class);
        $this->config = $container->get(ConfigInterface::class);
        $this->logger = $container->get(LoggerFactory::class);
        $this->name = $name;

        $config = $this->config->get('firebase.projects.' . $this->name);

        if (! $config) {
            throw new \RuntimeException("Firebase project [{$this->name}] not configured.");
        }

        // 封装配置获取
        if ($tenantId = data_get($config, 'auth.tenant_id')) {
            $this->factory = $this->factory->withTenantId($tenantId);
        }

        if ($credentials = data_get($config, 'credentials.file') ?? data_get($config, 'credentials')) {
            $this->factory = $this->factory->withServiceAccount($credentials);
        }

        if ($databaseUrl = data_get($config, 'database.url') ?? null) {
            $this->factory = $this->factory->withDatabaseUri($databaseUrl);
        }

        if ($authVariableOverride = data_get($config, 'database.auth_variable_override')) {
            $this->factory = $this->factory->withDatabaseAuthVariableOverride($authVariableOverride);
        }

        if ($defaultStorageBucket = data_get($config, 'storage.default_bucket')) {
            $this->factory = $this->factory->withDefaultStorageBucket($defaultStorageBucket);
        }

        if ($cacheStore = data_get($config, 'cache_store')) {
            $driver = data_get($config, "stores.{$cacheStore}.driver");
            $cacheDriver = make($driver, ['config' => data_get($config, "stores.{$cacheStore}")]);

            if ($cacheDriver instanceof CacheInterface) {
                $cacheDriver = new Psr16Adapter($cacheDriver);
            }

            $this->factory = $this->factory
                ->withVerifierCache($cacheDriver)
                ->withAuthTokenCache($cacheDriver);
        }

        if ($logChannel = data_get($config, 'logging.http_log_channel')) {
            $this->factory = $this->factory->withHttpLogger(
                $this->logger->make($logChannel)
            );
        }

        if ($logChannel = data_get($config, 'logging.http_debug_log_channel')) {
            $this->factory = $this->factory->withHttpDebugLogger(
                $this->logger->make($logChannel)
            );
        }

        $options = HttpClientOptions::default();

        if ($proxy = data_get($config, 'http_client_options.proxy')) {
            $options = $options->withProxy($proxy);
        }

        if ($timeout = data_get($config, 'http_client_options.timeout')) {
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
            $config = $this->config->get('firebase.projects.' . $this->name);
            $this->dynamicLinks = $this->factory->createDynamicLinksService(data_get($config, 'dynamic_links.default_domain'));
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
