<?php

namespace App\Common;

use Exception;

class Vite
{
    private static $instance = null;
    private string $viteHostProtocol = 'http://';
    private string $viteHost = 'localhost';
    private string $vitePort = '8880';
    private string $resourceDirectory = 'resources/';
    private array $moduleScripts = [];
    private bool $isScriptFilterAdded = false;

    public static function __callStatic($method, $params)
    {
        if (static::$instance == null) {
            static::$instance = new static();
            if (!static::isDevMode()) {
                (static::$instance)->viteManifest();
            }
        }
        return call_user_func_array(array(static::$instance, $method), $params);
    }

    function loadAssets()
    {
        $pluginUrl = PLUGIN_CONST_DIR;
        wp_enqueue_script('wp-vue-core', '//localhost:5173/src/main.js', [], time(), true);
        wp_localize_script('wp-vue-core', 'wpvue', [
            'url' => $pluginUrl
        ]);
    }

    public static function isDevMode(): bool
    {
        return defined('PLUGIN_CONST_DEVELOPMENT') && PLUGIN_CONST_DEVELOPMENT === true;
    }

    private function enqueueScript($handle, $src, $dependency = [], $version = null, $inFooter = false)
    {
        if (in_array($handle, (static::$instance)->moduleScripts)) {
            if (static::isDevMode()) {
                throw new Exception(__('This handle has been used', 'wp-base-plugin'));
            }
            return;
        }

        (static::$instance)->moduleScripts[] = $handle;
        (static::$instance)->loadScriptAsModule();

        if (!static::isDevMode()) {
            $assetFile = (static::$instance)->getFileFromManifest($src);
            $srcPath = static::getProductionFilePath($assetFile);
        } else {
            $srcPath = static::getDevPath() . $src;
        }

        wp_enqueue_script(
            $handle,
            $srcPath,
            $dependency,
            $version,
            $inFooter
        );
        return $this;
    }

    private function viteManifest()
    {
        if (!empty((static::$instance)->manifestData)) {
            return;
        }

        $manifestPath = PLUGIN_CONST_DIR . '/assets/.vite/manifest.json';
        if (!file_exists($manifestPath)) {
            throw new Exception(__('Vite Manifest Not Found. Run : npm run dev or npm run prod', 'wp-base-plugin'));
        }
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        $manifestData = $wp_filesystem->get_contents($manifestPath);
        if ($manifestData === false) {
            throw new Exception(__('Failed to read Vite Manifest file.', 'wp-base-plugin'));
        }
        (static::$instance)->manifestData = json_decode($manifestData, true);
    }


    /**
     * @throws Exception
     */
    private function getFileFromManifest($src)
    {
        if (!isset((static::$instance)->manifestData[(static::$instance)->resourceDirectory . $src]) && static::isDevMode()) {
            throw new Exception(esc_html("$src file not found in vite manifest, Make sure it is in rollupOptions input and build again"));
        }

        return (static::$instance)->manifestData[(static::$instance)->resourceDirectory . $src];
    }

    private static function getProductionFilePath($file): string
    {
        $assetPath = static::getAssetPath();
        if (isset($file['css']) && is_array($file['css'])) {
            foreach ($file['css'] as $key => $path) {
                wp_enqueue_style(
                    $file['file'] . '_' . $key . '_css',
                    $assetPath . $path
                );
            }
        }
        return ($assetPath . $file['file']);
    }

    private static function getAssetPath(): string
    {
        return PLUGIN_CONST_URL . 'assets/';
    }

    private static function getDevPath(): string
    {
        return (static::$instance)->viteHostProtocol . (static::$instance)->viteHost . ':' . (static::$instance)->vitePort . '/' . (static::$instance)->resourceDirectory;
    }

    public static function loadScriptAsModule()
    {
        if (!(static::$instance)->isScriptFilterAdded) {
            add_filter('script_loader_tag', function ($tag, $handle, $src) {
                return (static::$instance)->addModuleToScript($tag, $handle, $src);
            }, 10, 3);
            (static::$instance)->isScriptFilterAdded = true;
        }
    }

    private function addModuleToScript($tag, $handle, $src)
    {
        if (in_array($handle, (static::$instance)->moduleScripts)) {
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }
}
