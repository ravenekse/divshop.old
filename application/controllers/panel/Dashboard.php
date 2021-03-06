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
        $timestamp = array(
            'today' => getOnlyDate(time()),
            'yesterday' => getOnlyDate(time() - 86400),
            'twoDaysAgo' => getOnlyDate(time() - 172800),
            'threeDaysAgo' => getOnlyDate(time() - 259200),
            'fourDaysAgo' => getOnlyDate(time() - 345600),
            'fiveDaysAgo' => getOnlyDate(time() - 432000),
            'sixDaysAgo' => getOnlyDate(time() - 518400)
        );
        $whenPurchased = array(
            'today' => array(),
            'yesterday' => array(),
            'twoDaysAgo' => array(),
            'threeDaysAgo' => array(),
            'fourDaysAgo' => array(),
            'fiveDaysAgo' => array(),
            'sixDaysAgo' => array()
        );
        foreach($purchases as $purchase) {
            if($purchase['status'] == "success") {
                $profit += $purchase['profit'];
                switch(getOnlyDate($purchase['date'])) {
                    case $timestamp['today']:
                        array_push($whenPurchased['today'], $purchase);
                        break;
                    case $timestamp['yesterday']:
                        array_push($whenPurchased['yesterday'], $purchase);
                        break;
                    case $timestamp['twoDaysAgo']:
                        array_push($whenPurchased['twoDaysAgo'], $purchase);
                        break;
                    case $timestamp['threeDaysAgo']:
                        array_push($whenPurchased['threeDaysAgo'], $purchase);
                        break;
                    case $timestamp['fourDaysAgo']:
                        array_push($whenPurchased['fourDaysAgo'], $purchase);
                        break;
                    case $timestamp['fiveDaysAgo']:
                        array_push($whenPurchased['fiveDaysAgo'], $purchase);
                        break;
                    case $timestamp['sixDaysAgo']:
                        array_push($whenPurchased['sixDaysAgo'], $purchase);
                        break;
                }   
            }
        }
        switch(getDayNumber(time())) {
            case 1:
                $body['chartValues'] = count($whenPurchased['today']);
                $highest = count($whenPurchased['today']);
                break;
            case 2:
                $bodyD['chartValues'] = (count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']));
                break;
            case 3:
                $bodyD['chartValues'] = (count($whenPurchased['twoDaysAgo']) . ', ' . count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']), count($whenPurchased['twoDaysAgo']));
                break;
            case 4:
                $bodyD['chartValues'] = (count($whenPurchased['threeDaysAgo']) . ', ' . count($whenPurchased['twoDaysAgo']) . ', ' . count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']), count($whenPurchased['twoDaysAgo']), count($whenPurchased['threeDaysAgo']));
                break;
            case 5:
                $bodyD['chartValues'] = (count($whenPurchased['fourDaysAgo']) . ', ' . count($whenPurchased['threeDaysAgo']) . ', ' . count($whenPurchased['twoDaysAgo']) . ', ' . count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']), count($whenPurchased['twoDaysAgo']), count($whenPurchased['threeDaysAgo']), count($whenPurchased['fourDaysAgo']));
                break;
            case 6:
                $bodyD['chartValues'] = (count($whenPurchased['fiveDaysAgo']) . ', ' . count($whenPurchased['fourDaysAgo']) . ', ' . count($whenPurchased['threeDaysAgo']) . ', ' . count($whenPurchased['twoDaysAgo']) . ', ' . count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']), count($whenPurchased['twoDaysAgo']), count($whenPurchased['threeDaysAgo']), count($whenPurchased['fourDaysAgo']), count($whenPurchased['fiveDaysAgo']));
                break;
            case 7:
                $bodyD['chartValues'] = (count($whenPurchased['sixDaysAgo']) . ', ' . count($whenPurchased['sixDaysAgo']) . ', ' . count($whenPurchased['fiveDaysAgo']) . ', ' . count($whenPurchased['fourDaysAgo']) . ', ' . count($whenPurchased['threeDaysAgo']) . ', ' . count($whenPurchased['twoDaysAgo']) . ', ' . count($whenPurchased['yesterday']) . ', ' . count($whenPurchased['today']));
                $highest = max(count($whenPurchased['today']), count($whenPurchased['yesterday']), count($whenPurchased['twoDaysAgo']), count($whenPurchased['threeDaysAgo']), count($whenPurchased['fourDaysAgo']), count($whenPurchased['fiveDaysAgo']), count($whenPurchased['sixDaysAgo']));
                break;
        }

        if(in_array($highest, array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9))) {
            $bodyD['chartHighest'] = 10;
        } else {
            $bodyD['chartHighest'] = round($highest * 1.3, 2);
        }
        $transactionsCount = array(
            'today' => count($whenPurchased['today']),
            'yesterday' => count($whenPurchased['yesterday'])
        );
        if($transactionsCount['today'] == 0 && $transactionsCount['yesterday'] == 0) {
            $bodyD['percentTransactions'] = 0;
        } else if($transactionsCount['today'] == 0) {
            $bodyD['percentTransactions'] = -100;
        } else if($transactionsCount['yesterday'] == 0) {
            $bodyD['percentTransactions'] = 100;
        } else {
            $bodyD['percentTransactions'] = round($transactionsCount['today'] * 100 / $transactionsCount['yesterday'] - 100, 0);
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
                $_SESSION['messageDangerSmall'] = "Wyst??pi?? b????d podczas ????czenia z baz?? danych!";
                redirect($this->config->base_url('panel/dashboard'));
            }
            unset($data);
            $data['user'] = $_SESSION['name'];
            $data['section'] = "Notatki";
            $data['details'] = "U??ytkownik edytowa?? notatki.";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();
            $this->LogsM->add($data);
        
            $_SESSION['messageSuccessSmall'] = "Pomy??lnie zapisano notatki!";
            redirect($this->config->base_url('panel/dashboard'));
        } else {
            $_SESSION['messageDangerSmall'] = "Wyst??pi?? nieoczekiwany b????d!";
            redirect($this->config->base_url('panel/dashboard'));
        }
    }
}