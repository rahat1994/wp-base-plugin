<?php

namespace App\Common;

use App\Interfaces\Commons\AssetsLoaderInterface;

class LoadAssets implements AssetsLoaderInterface
{
    public function admin()
    {
        Vite::enqueueScript('wp-base-plugin-script', 'admin/main.js', array('jquery'), PLUGIN_CONST_VERSION, true);
    }

    public function frontend(){
        FrontEndVite::enqueueScript('wp-base-plugin-frontend-script', 'frontend/base.js', array('jquery'), PLUGIN_CONST_VERSION, true); 
    }
}
