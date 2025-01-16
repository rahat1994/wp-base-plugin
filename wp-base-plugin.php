<?php

use App\Common\PluginActivator;
use App\Common\PluginDeactivator;
use App\Interfaces\CPT\CPTInterface;
use App\Interfaces\ShortCodes\ShortcodeInterface;

/**
 * Plugin Name: Wp Base Plugin
 * Plugin URI: http://github.com/rahat1994
 * Description: A sample WordPress plugin to implement Vue with tailwind.
 * Author: Rahat Baksh
 * Author URI: http://github.com/rahat1994
 * Version: 1.0.0
 * Text Domain: wp-base-plugin
 * Domain Path: /languages
 * License: GPL2
 */
define('PLUGIN_CONST_URL', plugin_dir_url(__FILE__));
define('PLUGIN_CONST_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_CONST_VERSION', '1.0.0');
define('PLUGIN_CONST_DEVELOPMENT', true);

require_once(PLUGIN_CONST_DIR . 'vendor/autoload.php');

use App\Common\AjaxHandler;
use App\Common\LoadAssets;

class WpBasePlugin
{

    public AjaxHandler $apiHandler;
    public LoadAssets $assetsLoader;

    public PluginActivator $pluginActivator;

    public PluginDeactivator $pluginDeactivator;
    public array $CPTS = [];
    public array $shortCodes = [];
    public array $settingsPages = [];

    public array $crons = [];
    
    public function __construct(AjaxHandler $apiHandler, LoadAssets $assetsLoader, PluginActivator $pluginActivator, PluginDeactivator $pluginDeactivator)
    {
        $this->apiHandler = $apiHandler;
        $this->assetsLoader = $assetsLoader;
        $this->pluginActivator = $pluginActivator;
        $this->pluginDeactivator = $pluginDeactivator;
    }

    public function boot()
    {
        $this->activatePlugin();
        $this->registerHooks();
        $this->registerShortCodes();
        $this->registerSettingsPages();
        $this->registerApiRoutes();
        $this->registerCPT();
        $this->renderMenu();
        $this->registerCrons();
        $this->deactivatePlugin();
    }

    public function registerCPT(){
        foreach($this->CPTS as $cpt){
            if($cpt instanceof CPTInterface){
                $cpt->boot();
            }
        }
    }
    public function registerShortCodes() {
        foreach($this->shortCodes as $shortCode){
            if ($shortCode instanceof ShortcodeInterface) {
                $shortCode->boot();
            }
        }
    }

    public function registerCrons(){
        foreach($this->crons as $cron){
            $cron->boot();
        }
    }

    public function setShortCodes($shortCodes){
        $this->shortCodes = $shortCodes;
    }

    public function setCrons($crons){
        $this->crons = $crons;
    }

    public function registerSettingsPages() {
        foreach($this->settingsPages as $page){
            $page->boot();
        }
    }

    public function registerHooks()
    {
        add_action('init', array($this, 'loadTextDomain'));
    }

    public function activatePlugin()
    {
        register_activation_hook(__FILE__, function ($netWorkWide) {
            $this->pluginActivator->migrateDatabases($netWorkWide);
        });
    }

    public function deactivatePlugin(){
        register_deactivation_hook(__FILE__, function($networkWide){
            $this->pluginDeactivator->deactivate();
        });
    }

    public function registerApiRoutes()
    {
        
        $this->apiHandler->boot();
        
    }

    public function renderMenu()
    {
        add_action('admin_menu', function () {
            if (!current_user_can('manage_options')) {
                return;
            }
            global $submenu;
            add_menu_page(
                'WPBasePlugin',
                'WPBasePlugin',
                'manage_options',
                'wp-base-plugin',
                array($this, 'renderAdminPage'),
                'dashicons-editor-code',
                25
            );
            $submenu['pluginslug.php']['dashboard'] = array(
                'Dashboard',
                'manage_options',
                'admin.php?page=wp-base-plugin#/',
            );
            $submenu['pluginslug.php']['contact'] = array(
                'Contact',
                'manage_options',
                'admin.php?page=wp-base-plugin#/contact',
            );
        });
    }

    public function renderAdminPage()
    {
        $this->assetsLoader->admin();

        $translatable = apply_filters('wp-base-plugin/frontend_translatable_strings', array(
            'hello' => __('Hello', 'wp-base-plugin'),
        ));

        $pluginlowercase = apply_filters('pluginlowercase/admin_app_vars', array(
            'url' => PLUGIN_CONST_URL,
            'assets_url' => PLUGIN_CONST_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'action'        => 'wp_base_plugin',
            'nonce'         => wp_create_nonce('wp-base-plugin-nonce'),
            'i18n' => $translatable
        ));

        wp_localize_script('wp-base-plugin-script', 'wpbaseplugin', $pluginlowercase);

        echo '<div id="app"></div>';
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain('wp-base-plugin', false, basename(dirname(__FILE__)) . '/languages');
    }
}

$container = ServiceContainer::getInstance()->getContainer();
$wpBasePlugin  = $container->get(WpBasePlugin::class);
$wpBasePlugin->boot();
