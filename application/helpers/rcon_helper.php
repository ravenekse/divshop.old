<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'libraries/sourcequery-lib/bootstrap.php');

function checkRconConnection($ipAddress, $rconPort, $rconPass) {
    $query = new \xPaw\SourceQuery\SourceQuery();

    try {
        $query->Connect($ipAddress, $rconPort, 1, \xPaw\SourceQuery\SourceQuery::SOURCE);
        $query->SetRconPassword($rconPass);
        return array('status' => true, 'message' => 'Connected.');
    } catch(Exception $e) {
        return array('status' => false, 'message' => 'Connection Failed.');
    } finally {
        $query->Disconnect();
    }
}

function sendRconCommand($ipAddress, $rconPort, $rconPass, $commands, $player = null) {
    $query = new \xPaw\SourceQuery\SourceQuery();

    try {
        $query->Connect($ipAddress, $rconPort, 1, \xPaw\SourceQuery\SourceQuery::SOURCE);
        $query->SetRconPassword($rconPass);
        $resp = array();
        foreach($commands as $command) {
            if($player != null) {
                array_push($resp, $query->Rcon(str_replace(array('{buyer}', '{BUYER}'), $player, $command)));
            } else {
                array_push($resp, $query->Rcon($command));
            }
        }
        return array('status' => true, 'message' => 'Yay! Polecenia zostały wysłane na serwer', 'response' => $resp);
    } catch(Exception $e) {
        return array('status' => false, 'message' => 'O nie! Wystąpił błąd podczas komunikacji z serwerem');
    } finally {
        $query->Disconnect();
    }
}