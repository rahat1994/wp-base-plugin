<?php

use App\Common\AjaxHandler;
use App\Common\LoadAssets;
use App\Common\Router;
use App\CPT\FeedCPT;
use App\Interfaces\Commons\ApiHandlerInterface;
use App\Interfaces\Commons\AssetsLoaderInterface;
use App\Shortcodes\FeedShortCode;
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
        $this->container = $containerBuilder->addDefinitions([
            AssetsLoaderInterface::class => DI\autowire(LoadAssets::class),
            ApiHandlerInterface::class => DI\autowire(AjaxHandler::class)->property('routesFile', PLUGIN_CONST_DIR . '/routes/routes.php'),
            Router::class => DI\autowire(Router::class),
            WpBasePlugin::class => DI\autowire(WpBasePlugin::class)
                                    ->property('CPTS', self::getCPTS())
                                    ->property('shortCodes', self::getShortCodes()),
            FeedCPT::class => DI\autowire(FeedCPT::class),
            UrlValidator::class => DI\autowire(UrlValidator::class),
            RegexValidator::class => DI\autowire(RegexValidator::class),
        ])->build();
    }

    // public function pluginBoot()
    // {
    //     $this->container->get(WpBasePlugin::class)->boot();
    // }

    public static function getCPTS(){
        return [
            DI\get(FeedCPT::class)
        ];
    }

    public static function getShortCodes(){
        return [
            DI\get(FeedShortCode::class)
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
