<?php

use App\Common\AjaxHandler;
use App\Common\LoadAssets;
use App\Common\PluginActivator;
use App\Common\PluginDeactivator;
use App\Common\Router;
use App\CPT\FeedCPT;
use App\Cron\PlatformCallCron;
use App\Cron\SingleFeedCacheRegenerationCron;
use App\Handlers\FeedCreationEventHandler;
use App\Interfaces\Commons\ApiHandlerInterface;
use App\Interfaces\Commons\AssetsLoaderInterface;
use App\PlatformClients\RedditClient;
use App\SettingsPage\WPBaseSettingsPage;
use App\ShortCodes\FeedShortCode;
use App\Validators\RegexValidator;
use App\Validators\UrlValidator;
use League\Container\Container;

class ServiceContainer
{
    private static $instance;
    private Container $container;

    private final function __construct()
    {
        $this->container = new Container();

        $this->container->delegate(
            new League\Container\ReflectionContainer()
        );

        $this->container->add(LoadAssets::class);
        $this->container->add(Router::class);
        $this->container->add(AjaxHandler::class)->addArgument(Router::class);
        $this->container->add(PlatformCallCron::class);
        $this->container->add(SingleFeedCacheRegenerationCron::class)
            ->addArgument(RedditClient::class);
        $this->container->add(FeedCreationEventHandler::class)
            ->addArgument(RedditClient::class);
        $this->container->add(PluginDeactivator::class);
        $this->container->add(WpBasePlugin::class)
            ->addArguments([AjaxHandler::class, LoadAssets::class, PluginActivator::class, PluginDeactivator::class])
            ->addMethodCall('setShortCodes', [$this->getShortCodes()])
            ->addMethodCall('setCrons', [$this->getCrons()])
            ->addMethodCall('setEventHandlers', [$this->getHandlers()]);
        $this->container->add(FeedCPT::class);
        $this->container->add(UrlValidator::class);
        $this->container->add(RegexValidator::class);
        $this->container->add(FeedShortCode::class);
        $this->container->add(WPBaseSettingsPage::class);

        // $this->container = $containerBuilder->addDefinitions([
        //     AssetsLoaderInterface::class => DI\autowire(LoadAssets::class),
        //     ApiHandlerInterface::class => DI\autowire(AjaxHandler::class)->property('routesFile', PLUGIN_CONST_DIR . '/routes/routes.php'),
        //     Router::class => DI\autowire(Router::class),
        //     WpBasePlugin::class => DI\autowire(WpBasePlugin::class)
        //                             ->property('shortCodes', self::getShortCodes()),
        //     FeedCPT::class => DI\autowire(FeedCPT::class),
        //     UrlValidator::class => DI\autowire(UrlValidator::class),
        //     RegexValidator::class => DI\autowire(RegexValidator::class),
        //     FeedShortCode::class => DI\autowire(FeedShortCode::class),
        //     WPBaseSettingsPage::class => DI\autowire(WpBaseSettingsPage::class),

        // ])->build();
    }

    // public static function getSettingsPages(){
    //     return [
    //         DI\get(WPBaseSettingsPage::class)
    //     ];
    // }
    // public static function getCPTS(){
    //     return [
    //         DI\get(FeedCPT::class)
    //     ];
    // }

    public function getShortCodes()
    {
        return [
            $this->container->get(FeedShortCode::class)
        ];
    }

    public function getCrons()
    {
        return [
            $this->container->get(PlatformCallCron::class),
            $this->container->get(SingleFeedCacheRegenerationCron::class)
        ];
    }

    public function getHandlers()
    {
        return [
            $this->container->get(FeedCreationEventHandler::class)
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
