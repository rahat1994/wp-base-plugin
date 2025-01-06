<?php

use App\Common\AjaxHandler;
use App\Common\LoadAssets;
use App\Common\Router;
use App\CPT\FeedCPT;
use App\Interfaces\Commons\ApiHandlerInterface;
use App\Interfaces\Commons\AssetsLoaderInterface;
use App\Validators\RegexValidator;
use App\Validators\UrlValidator;
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
            WpBasePlugin::class => DI\autowire(WpBasePlugin::class)->property('CPTS', self::getsCPTS()),
            FeedCPT::class => DI\autowire(FeedCPT::class),
            UrlValidator::class => DI\autowire(UrlValidator::class),
            RegexValidator::class => DI\autowire(RegexValidator::class),
        ]);

        $this->container = $containerBuilder->build();
    }

    public static function getsCPTS(){
        return [
            DI\get(FeedCPT::class)
        ];
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
