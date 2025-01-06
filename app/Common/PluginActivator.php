<?php

namespace App\Common;

if (!defined('ABSPATH')) {
    exit;
}

use App\Interfaces\Commons\PluginActivatorInterface;

class PluginActivator implements PluginActivatorInterface
{
    private function migrate() {}
}
