<?php

/**
 * Plugin Name: Wp Base Plugin
 * Plugin URI: http://github.com/rahat1994
 * Description: A sample WordPress plugin to implement Vue with tailwind.
 * Author: Rahat Baksh
 * Author URI: http://github.com/rahat1994
 * Version: 1.0.0
 * Text Domain: wp-base-plugin
 * Domain Path: /languages
 */
define('PLUGIN_CONST_URL', plugin_dir_url(__FILE__));
define('PLUGIN_CONST_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_CONST_VERSION', '1.0.0');
define('PLUGIN_CONST_DEVELOPMENT', false);

require_once(PLUGIN_CONST_DIR . 'vendor/autoload.php');
require_once(PLUGIN_CONST_DIR . 'bootstrap/ServiceContainer.php');


use App\Common\LoadAssets;
use App\Interfaces\AssetsLoaderInterface;

class WpBasePlugin
{

    public function boot()
    {
        $this->activatePlugin();
        $this->registerHooks();
        $this->registerShortCodes();
        $this->renderMenu();
    }

    public function registerHooks()
    {
        add_action('init', array($this, 'loadTextDomain'));
    }

    public function registerShortCodes() {}

    public function activatePlugin()
    {
        register_activation_hook(__FILE__, function ($newWorkWide) {});
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
        $container = ServiceContainer::getInstance()->getContainer();

        $loadAssets = $container->get(AssetsLoaderInterface::class);
        $loadAssets->admin();

        $translatable = apply_filters('wp-base-plugin/frontend_translatable_strings', array(
            'hello' => __('Hello', 'pluginslug'),
        ));

        $pluginlowercase = apply_filters('pluginlowercase/admin_app_vars', array(
            'url' => PLUGIN_CONST_URL,
            'assets_url' => PLUGIN_CONST_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'i18n' => $translatable
        ));

        wp_localize_script('wp-base-plugin-script-boot', 'wpbaseplugin', $pluginlowercase);

        echo '<div id="app"></div>';
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain('wp-base-plugin', false, basename(dirname(__FILE__)) . '/languages');
    }
}

(new WpBasePlugin())->boot();
