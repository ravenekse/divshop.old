<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

function checkCode($userId, $serviceId, $serviceNumber, $code)
{
    if (preg_match('/^[A-Za-z0-9]{8}$/', $code)) {
        $url = 'https://microsms.pl/api/v2/multi.php?userid='.$userId.'&code='.$code.'&serviceid='.$serviceId;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $api = json_decode($response, true);

        /**  Error responses  */
        if (isset($api['data']['errorCode'])) {
            if ($api['data']['errorCode'] == 1) {
                return ['status' => false, 'message' => 'Przykro nam, ale podany kod jest nieprawidłowy.'];
            } else {
                return ['status' => false, 'message' => 'Niestety wystąpił błąd podczas pobierania informacji z serwera płatności.'];
            }
        }
        if ($api['data']['used'] == 1) {
            return ['status' => false, 'message' => 'Oops, podany kod został już wykorzystany.'];
        }
        if ($api['data']['service'] != $serviceId || $api['data']['number'] != $serviceNumber) {
            return ['status' => false, 'message' => 'Przykro nam, ale podany kod jest nieprawidłowy.'];
        }
        if (isset($api['connect']) && $api['connect'] == true) {
            if ($api['data']['status'] == 1) {
                return ['status' => true, 'message' => 'Yay! Usługa została pomyślnie zrealizowana!'];
            } else {
                return ['status' => false, 'message' => 'Przykro nam, ale podany kod jest nieprawidłowy.'];
            }
        }
    } else {
        return ['status' => false, 'message' => 'Przykro nam, ale podany kod jest nieprawidłowy.'];
    }
}
