<?php

use App\Common\AjaxHandler;
use App\Common\LoadAssets;
use App\Common\Router;
use App\Interfaces\Commons\ApiHandlerInterface;
use App\Interfaces\Commons\AssetsLoaderInterface;
use DI\Container;
use DI\ContainerBuilder;

class ServiceContainer
{
    private static $instance;
    private Container $container;

    private final function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            AssetsLoaderInterface::class => DI\autowire(LoadAssets::class),
            ApiHandlerInterface::class => DI\autowire(AjaxHandler::class),
            Router::class => DI\autowire(Router::class),
        ]);

        $this->container = $containerBuilder->build();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getContainer()
    {
        return $this->container;
    }
}
