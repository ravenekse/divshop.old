<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }
        
    public function redirectToDashboard() {
        redirect($this->config->base_url('panel/dashboard'));
    }
    public function index() {

        require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
        require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('AdminsM');
        $this->load->model('PurchasesM');
        $this->load->model('ServicesM');
        $this->load->model('ServersM');
        $this->load->model('FailedLoginsM');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        
		/**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Dashboard";
		$this->load->view('components/Header', $headerD);

        /**  Body section  */
        $purchases = $this->PurchasesM->getAll();
        $profitstat = 0;
        foreach($purchases as $purchase) {
            $profitstat += $purchase['profit'];
        }
        $bodyD['statsData'] = array(
            'services'    =>   count($this->ServicesM->getAll()),
            'servers'     =>   count($this->ServersM->getAll()),
            'purchases'   =>   count($this->PurchasesM->getAll()),
            'profit'      =>   $profitstat
        );
        $bodyD['failedLogins'] = $this->FailedLoginsM->getAllLogs(0, 10);
        $bodyD['modules'] = $this->ModulesM->getAll();
        $services = $this->ServicesM->getAll();
        $servers = $this->ServersM->getFive();
        $bodyD['servers'] = array();
        foreach($servers as $server) {
            try {
                $query = new MinecraftPing($server['ip'], $server['port']);
                $result = $query->Query();
                $version = $result['version']['name'];
                $version = str_split($version);
                $server['status']['version'] = "";
                foreach($version as $char) { if((is_numeric($char)) || ($char == ".")) { $server['status']['version'] .= $char; } }
                $server['status']['onlinePlayers'] = $result['players']['online'];
                $server['status']['maxPlayers'] = $result['players']['max'];
            } catch (MinecraftPingException $e) {
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
            } finally {
                if(isset($query)) $query->Close();
            }
            array_push($bodyD['servers'], $server);
        }
        $bodyD['purchases'] = array();
        $profit = 0;
        $todayTS = time(); $today = getOnlyDate($todayTS);
        $yesterdayTS = $todayTS - 86400; $yesterday = getOnlyDate($yesterdayTS);
        $twoDaysAgoTS = $yesterdayTS - 86400; $twoDaysAgo = getOnlyDate($twoDaysAgoTS);
        $threeDaysAgoTS = $twoDaysAgoTS - 86400; $threeDaysAgo = getOnlyDate($threeDaysAgoTS);
        $fourDaysAgoTS = $threeDaysAgoTS - 86400; $fourDaysAgo = getOnlyDate($fourDaysAgoTS);
        $fiveDaysAgoTS = $fourDaysAgoTS - 86400; $fiveDaysAgo = getOnlyDate($fiveDaysAgoTS);
        $sixDaysAgoTS = $fiveDaysAgoTS - 86400; $sixDaysAgo = getOnlyDate($sixDaysAgoTS);
        $todayPurchases = array(); $yesterdayP = array(); $twoDaysAgoP = array(); $threeDaysAgoP = array(); $fourDaysAgoP = array(); $fiveDaysAgoP = array(); $sixDaysAgoP = array();
        foreach($purchases as $purchase) {
            $profit += $purchase['profit'];
            if(getOnlyDate($purchase['date']) == $today) array_push($todayPurchases, $purchase);
            if(getOnlyDate($purchase['date']) == $yesterday) array_push($yesterdayP, $purchase);
            if(getOnlyDate($purchase['date']) == $twoDaysAgo) array_push($twoDaysAgoP, $purchase);
            if(getOnlyDate($purchase['date']) == $threeDaysAgo) array_push($threeDaysAgoP, $purchase);
            if(getOnlyDate($purchase['date']) == $fourDaysAgo) array_push($fourDaysAgoP, $purchase);
            if(getOnlyDate($purchase['date']) == $fiveDaysAgo) array_push($fiveDaysAgoP, $purchase);
            if(getOnlyDate($purchase['date']) == $sixDaysAgo) array_push($sixDaysAgoP, $purchase);
        }
        if(getDayNumber($todayTS) == 7) { $bodyD['chartValues'] = count($sixDaysAgoP) . ", " . count($fiveDaysAgoP) . ", " . count($fourDaysAgoP) . ", " . count($threeDaysAgoP) . ", " . count($twoDaysAgoP) . ", " . count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP), count($twoDaysAgoP), count($threeDaysAgoP), count($fourDaysAgoP), count($fiveDaysAgoP), count($sixDaysAgoP)); }
        if(getDayNumber($todayTS) == 6) { $bodyD['chartValues'] = count($fiveDaysAgoP) . ", " . count($fourDaysAgoP) . ", " . count($threeDaysAgoP) . ", " . count($twoDaysAgoP) . ", " . count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP), count($twoDaysAgoP), count($threeDaysAgoP), count($fourDaysAgoP), count($fiveDaysAgoP)); }
        if(getDayNumber($todayTS) == 5) { $bodyD['chartValues'] = count($fourDaysAgoP) . ", " . count($threeDaysAgoP) . ", " . count($twoDaysAgoP) . ", " . count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP), count($twoDaysAgoP), count($threeDaysAgoP), count($fourDaysAgoP)); }
        if(getDayNumber($todayTS) == 4) { $bodyD['chartValues'] = count($threeDaysAgoP) . ", " . count($twoDaysAgoP) . ", " . count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP), count($twoDaysAgoP), count($threeDaysAgoP)); }
        if(getDayNumber($todayTS) == 3) { $bodyD['chartValues'] = count($twoDaysAgoP) . ", " . count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP), count($twoDaysAgoP)); }
        if(getDayNumber($todayTS) == 2) { $bodyD['chartValues'] = count($yesterdayP) . ", " . count($todayPurchases); $highest = max(count($todayPurchases), count($yesterdayP)); }
        if(getDayNumber($todayTS) == 1) { $bodyD['chartValues'] = count($todayPurchases); $highest = count($todayPurchases); }
        if(in_array($highest, array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9))) { $bodyD['chartHighest'] = 10; } else { $bodyD['chartHighest'] = round(($highest * 1.3), 2); }
        $yesterdayTransactions = count($yesterdayP);
        $todayTransactions = count($todayPurchases);
        if(($todayTransactions == 0) && ($yesterdayTransactions == 0)) {
            $bodyD['percentTransactions'] = 0;
        } else if($todayTransactions == 0) {
            $bodyD['percentTransactions'] = -100;
        } else if($yesterdayTransactions == 0) {
            $bodyD['percentTransactions'] = 100;
        } else {
            $bodyD['percentTransactions'] = round((($todayTransactions * 100) / $yesterdayTransactions) - 100, 0);
        }

        $bodyD['lastPurchases'] = $this->PurchasesM->getLastFive();
        foreach($servers as $server) {
            for($i = 0; $i < count($services); $i++) {
                if($services[$i]['server'] == $server['id']) {
                    for($x = 0; $x < count($bodyD['lastPurchases']); $x++) {
                        if($bodyD['lastPurchases'][$x]['service'] == $services[$i]['id']) {
                            $bodyD['lastPurchases'][$x]['service'] = $services[$i]['name']." (ID: #".$services[$i]['id'].")";
                            $bodyD['lastPurchases'][$x]['server'] = $server['name'];
                        } else if(is_numeric($bodyD['lastPurchases'][$x]['service'])) {
                            $bodyD['lastPurchases'][$x]['service'] = '<b class="text-danger">Deleted</b>';
                            $bodyD['lastPurchases'][$x]['server'] = $server['name'];
                        }
                    }
                }
            }
        }
        
        $this->load->view('panel/Dashboard', $bodyD);
        
    }

    public function notesSave() {
        $this->form_validation->set_rules('notesContent', 'notesContent', 'trim');
    
        if($this->form_validation->run() === TRUE) {
            sleep(1);
            $data['panelNotes'] = ($this->input->post('notesContent') == null) ? null : $this->input->post('notesContent');
            if(!$this->SettingsM->update($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/dashboard'));
            }
            unset($data);
            $data['user'] = $_SESSION['name'];
            $data['section'] = "Notatki";
            $data['details'] = "Użytkownik edytował notatki.";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();
            $this->LogsM->add($data);
        
            $_SESSION['messageSuccessSmall'] = "Pomyślnie zapisano notatki!";
            redirect($this->config->base_url('panel/dashboard'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd!";
            redirect($this->config->base_url('panel/dashboard'));
        }
    }
}