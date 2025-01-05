<?php

namespace App\Common;

use App\Interfaces\Commons\AssetsLoaderInterface;

class LoadAssets implements AssetsLoaderInterface
{
    public function admin()
    {
        Vite::enqueueScript('wp-base-plugin-script-boot', 'admin/main.js', array('jquery'), PLUGIN_CONST_VERSION, true);
    }
}
