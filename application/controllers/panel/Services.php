<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ServicesM');
        $this->load->model('PaymentsM');
        $this->load->model('ServersM');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Usługi";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $services = $this->ServicesM->getAll();
        $servers = $this->ServersM->getAll();
        $bodyD['servers'] = $servers;
        $bodyD['modules'] = $this->ModulesM->getAll();
        $bodyD['services'] = $this->ServicesM->getAll();
        if($headerD['settings']['smsOperator'] == 0) {
            $headerD['smsOperator'] = null;
        } else {
            $headerD['smsOperator'] = $this->PaymentsM->get($headerD['settings']['smsOperator']);
            $headerD['smsOperator']['config'] = json_decode($headerD['smsOperator']['config']);
        }

        foreach($servers as $server) {
            for($i = 0; $i < count($services); $i++) {
                if(($bodyD['services'][$i]['smsConfig'] != null) && (!is_array($bodyD['services'][$i]['smsConfig']))) {
                    $bodyD['services'][$i]['smsConfig'] = json_decode($bodyD['services'][$i]['smsConfig'], true);
                }
                if(!is_array($bodyD['services'][$i]['commands'])) {
                    $bodyD['services'][$i]['commands'] = explode('; ', $bodyD['services'][$i]['commands']);
                }
                if($bodyD['services'][$i]['server'] == $server['id']) {
                    $bodyD['services'][$i]['server'] = $server['name'];
                    $bodyD['services'][$i]['serverid'] = $server['id'];
                }
            }
        }

        $this->load->view('panel/Services', $bodyD);
    }
}