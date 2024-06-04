<?php

/**
 * The Assets' Facade.
 *
 * @package WPStrap/Vite
 */

declare(strict_types=1);

namespace ViteHelpers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

/**
 * Class Assets
 *
 * @method static AssetsService register(array $config)
 * @method static AssetsService get(string $file = '')
 * @method static AssetsService css(string $entry, string $file = '')
 * @method static AssetsService js(string $entry, string $file = '')
 * @method static AssetsService image(string $entry, string $file = '')
 * @method static AssetsService font(string $entry, string $file = '')
 * @method static AssetsService svg(string $entry, string $file = '')
 * @method static AssetsService version()
 * @method static AssetsService deps(string $key = '')
 * @method static ScriptService enqueueScript(string $handle, string $file ='', array $deps =[], bool $footer = true)
 * @method static ScriptService registerScript(string $handle, string $file ='', array $deps =[], bool $footer = true)
 * @method static StyleService enqueueStyle(string $handle, string $file ='', array $deps =[], string $media = 'all')
 * @method static StyleService registerStyle(string $handle, string $file ='', array $deps =[], string $media = 'all')
 * @method static AssetsService getRoot()
 * @method static AssetsService getOutDir()
 * @method static AssetsService getEntry()
 */
class Assets
{
    /**
     * The Assets.
     *
     * @var AssetsService
     */
    protected static AssetsService $assets;

    /**
     * The Dev Server.
     *
     * @var DevServer
     */
    protected static DevServer $devServer;

    /**
     * PSR Container.
     *
     * @var ContainerInterface
     */
    protected static ContainerInterface $container;

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array<string|int, mixed> $args
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::resolveInstance();

        if (!isset($instance)) {
            throw new RuntimeException('[Vite] Assets service could not be resolved.');
        }

        return $instance->{$method}(...$args);
    }

    /**
     * Resolve the facade instance.
     *
     * @return AssetsService|null
     */
    protected static function resolveInstance(): ?AssetsService
    {
        if (!isset(static::$assets) && !isset(static::$container)) {
            static::$assets = new AssetsService();
            static::$devServer = new DevServer(static::$assets);
        }

        return static::$assets;
    }

    /**
     * Set facade(s).
     *
     * @param AssetsService | DevServer ...$instances
     *
     * @return void
     */
    public static function setFacade(...$instances)
    {
        foreach ($instances as $instance) {
            if ($instance instanceof AssetsService) {
                static::$assets = $instance;
            } elseif ($instance instanceof DevServer) {
                static::$devServer = $instance;
            }
        }
    }

    /**
     * Set facade accessor.
     *
     * @param ContainerInterface $container
     *
     * @return void
     */
    public static function setFacadeAccessor(ContainerInterface $container)
    {
        static::$container = $container;

        foreach ([AssetsService::class, DevServer::class] as $interface) {
            if (static::$container->has($interface)) {
                static::setFacade(static::resolveFacadeAccessor($interface));
            }
        }
    }

    /**
     * Get the registered class from the container.
     *
     * @param string $id
     *
     * @return mixed|void
     */
    protected static function resolveFacadeAccessor(string $id)
    {
        try {
            return static::$container->get($id);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            if (\defined('WP_DEBUG') && \WP_DEBUG) {
                \wp_die(\esc_html($e->getMessage()));
            } else {
                if (\defined('WP_DEBUG_LOG') && \WP_DEBUG_LOG) {
                    \error_log(\esc_html($e->getMessage())); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                }
            }
        }
    }

    /**
     * Get the dev server instance.
     *
     * @return DevServer
     */
    public static function devServer(): DevServer
    {
        return static::$devServer;
    }
}
