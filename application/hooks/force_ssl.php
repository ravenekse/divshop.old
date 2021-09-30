<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

function force_ssl()
{
    $CI = &get_instance();
    $class = $CI->router->fetch_class();
    $exclude = ['client'];
    if ($CI->config->item('force_ssl') == true) {
        if (!in_array($class, $exclude)) {
            // redirecting to ssl.
            $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
            if ($_SERVER['SERVER_PORT'] != 443) {
                redirect($CI->uri->uri_string());
            }
        } else {
            // redirecting with no ssl.
            $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
            if ($_SERVER['SERVER_PORT'] == 443) {
                redirect($CI->uri->uri_string());
            }
        }
    }
}
