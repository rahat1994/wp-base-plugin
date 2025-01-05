<?php

namespace App\Common;

use App\Common\Request;
use App\Common\Router;
use App\Interfaces\Commons\ApiHandlerInterface;
use ServiceContainer;

class AjaxHandler implements ApiHandlerInterface
{
    public function __construct(public Router $router) {}
    /**
     * AjaxHandler constructor.
     */
    public function boot($apiNamespace)
    {
        add_action('wp_ajax_' . $apiNamespace, [$this, 'handleRequest']);
    }

    public function handleRequest()
    {
        $this->verify($_REQUEST);
        $this->router->direct(Request::ajaxRoute(), Request::method());
    }

    public function getAccessRole()
    {
        return apply_filters('DLCT_LOG_admin_access_role', 'manage_options');
    }

    public function verify($request)
    {
        if (!wp_doing_ajax()) {
            return;
        }
        if (!current_user_can($this->getAccessRole())) {
            return;
        }

        if (!wp_verify_nonce($request['nonce'], 'dlct-nonce')) {
            wp_send_json_error(['message' => 'Error: Nonce error!']);
        }
    }
}
