<?php

namespace App\Common;

use App\Common\Request;
use App\Common\Router;
use App\Interfaces\Commons\ApiHandlerInterface;

class AjaxHandler implements ApiHandlerInterface
{
    public Router $router;
    public string $routesFile = PLUGIN_CONST_DIR . 'routes/routes.php';
    public function __construct(Router $router) {
        $this->router = $router;
    }
    /**
     * AjaxHandler constructor.
     */
    public function boot($apiNamespace = 'wp_base_plugin')
    {
        add_action('wp_ajax_' . $apiNamespace, [$this, 'handleRequest']);
        
    }

    public function handleRequest()
    {
        $this->verify($_REQUEST);
        $this->router->load($this->routesFile)->direct(Request::ajaxRoute(), Request::method());
    }

    public function getAccessRole()
    {
        return apply_filters('WP_BASE_admin_access_role', 'manage_options');
    }

    public function verify($request)
    {
        if (!wp_doing_ajax()) {
            return;
        }
        if (!current_user_can($this->getAccessRole())) {
            return;
        }

        if (!wp_verify_nonce($request['nonce'], 'wp-base-plugin-nonce')) {
            wp_send_json_error(['message' => 'Error: Nonce error!']);
        }
    }
}
