<?php

namespace App\Common;

class LoadAssets
{
    public function admin()
    {
        Vite::enqueueScript('wp-base-plugin-script-boot', 'admin/main.js', array('jquery'), PLUGIN_CONST_VERSION, true);
    }
}
