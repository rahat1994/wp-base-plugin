<?php

use App\Common\LoadAssets;
use App\Interfaces\AssetsLoaderInterface;
use DI\ContainerBuilder;

class ServiceContainer
{
    private static $instance;
    private $container;

    private final function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            AssetsLoaderInterface::class => DI\create(LoadAssets::class),
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
