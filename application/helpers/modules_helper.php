<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

/**   Badges   */
function getModuleName($module)
{
    switch ($module) {
        case 'news':
            return 'Aktualności';
            break;
        case 'bans':
            return 'Bany';
            break;
        case 'stats':
            return 'Statystyki';
            break;
        case 'antibot':
            return 'Antybot';
            break;
        case 'vouchers':
            return 'Vouchery';
            break;
        case 'lastbuyers':
            return 'Ostatnie zakupy';
            break;
        case 'pages':
            return 'Własne strony';
            break;
        default:
            return 'Brak nazwy';
    }
}

function getModuleIcon($module)
{
    switch ($module) {
        case 'news':
            return 'far fa-newspaper';
            break;
        case 'bans':
            return 'fas fa-lock';
            break;
        case 'stats':
            return 'fas fa-chart-line';
            break;
        case 'antibot':
            return 'fas fa-robot';
            break;
        case 'vouchers':
            return 'fas fa-ticket-alt';
            break;
        case 'lastbuyers':
            return 'fas fa-shopping-basket';
            break;
        case 'pages':
            return 'far fa-file';
            break;
        default:
            return 'fas fa-layer-group';
    }
}

function getModuleDescription($module)
{
    switch ($module) {
        case 'news':
            return 'Ten moduł pozwala na dodawanie aktualności na stronie głównej sklepu';
            break;
        case 'bans':
            return 'Ten moduł pozwala na wyświetlanie listy banów na stronie sklepu';
            break;
        case 'stats':
            return 'Ten moduł pozwala na wyświetlanie statystyk graczy na stronie sklepu';
            break;
        case 'antibot':
            return 'Ten moduł pozwala na weryfikację graczy przed wejściem na serwer';
            break;
        case 'vouchers':
            return 'Ten moduł pozwala na dodawanie voucherów, które służą do odbierania usług';
            break;
        case 'lastbuyers':
            return 'Ten moduł pozwala na wyświetlanie listy ostatnich kupujących usług w sklepie';
            break;
        case 'pages':
            return 'Ten moduł pozwala na tworzenie własnych stron oraz dodawanie linków do menu';
            break;
        default:
            return 'Brak opisu dla tego modułu';
    }
}
