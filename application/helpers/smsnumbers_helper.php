<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

function getSmsNumber($operator)
{
    switch ($operator) {
        case 1: // Microsms.pl
            return ['71480' => 1, '72480' => 2, '73480' => 3, '74480' => 4, '75480' => 5, '76480' => 6, '79480' => 9, '91400' => 14, '91900' => 19, '92022' => 20, '92521' => 25, '92550' => 25];
        default:
            return null;
    }
}

function getNettoPrice($number, $operator)
{
    $numbers = getSmsNumber($operator);
    foreach ($numbers as $numb => $cost) {
        if ($numb == $number) {
            return $cost;
        }
    }

    return null;
}

function getBruttoPrice($number, $operator)
{
    foreach (getSmsNumber($operator) as $numb => $cost) {
        if ($numb == $number) {
            return number_format(round(($cost + (0.23 * $cost)), 2), 2, '.', ' ');
        }
    }

    return null;
}
