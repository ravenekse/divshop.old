<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

/**   Badges   */
function getLogBadge($section) {
    switch($section) {
        case "Logowanie":
            return '<span class="badge badge-dark badge-sm">' . $section . '</span>';
        default:
            return '<span class="badge badge-primary badge-sm">' . $section . '</span>';
    }
}

/**   Switch themes   */
function getPageTheme($theme = 'custom') {
    $theme = strtolower($theme);
    switch($theme):
        case 'defaultlight':
            return base_url('assets/themes/divshop-material.light.css');
            break;
        case 'defaultdark':
            return base_url('assets/themes/divshop-material.dark.css');
            break;
        default:
            return base_url('assets/themes/divshop-material.dark.css');
    endswitch;
}