<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {
	public function __construct() {
		parent::__construct();
    }

    public function index($serverId = null, $serverName = null, $serviceId = null, $serviceName = null) {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {

			/**  Head section  */
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			/**  Body section  */
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = array(
                'title' => $bodyD['pageBreak']['pageBreakTitle'],
                'description' => $bodyD['pageBreak']['pageBreakDescription']
            );
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if($serviceId == null && $serviceName == null) {
                $this->load->view('errors/html/error_404.php');
            } else {

                require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                $this->load->model('ServersM');
                $this->load->model('PurchasesM');
                $this->load->model('ServicesM');
                $this->load->model('ModulesM');
                $this->load->model('PaymentsM');
                $this->load->model('NewsM');
                $this->load->model('PagesM');
                $api = new DIVShopAPI();
                $bodyD['divsAPI'] = array(
                    'divsVersion'  =>  $api->get_current_version()
                );
                $bodyD['modules'] = $this->ModulesM->getAll();
                $bodyD['news'] = $this->NewsM->getAll();
                $bodyD['pages'] = $this->PagesM->getAll();
                $bodyD['server'] = $this->ServersM->getBy('id', $serverId);

                if(!$bodyD['service'] = $this->ServicesM->getBy('id', $serviceId)) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }

                if($bodyD['service']['server'] != $serverId) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }

                if(getShopUrl($bodyD['server']['name']) != $serverName) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }

                if(getServiceUrl($bodyD['service']['name']) != $serviceName) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                } else {

                    if($bodyD['service']['serviceActive'] != 0) {
                        
                        /**  Head section  */
                        $headerD['settings'] = $this->SettingsM->getSettings();
                        $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                        $headerD['pageSubtitle'] = $bodyD['service']['name'];
                        $this->load->view('components/Header', $headerD);

                        /**  Body section  */
                        $bodyD['service']['smsConfig'] = json_decode($bodyD['service']['smsConfig'], true);

                        $sms = $this->PaymentsM->get(1);
                        $paypal = $this->PaymentsM->get(2);
                        $transfer = $this->PaymentsM->get(3);
                        $payments = array(
                            'sms'       =>  json_decode($sms['config'], true),
                            'paypal'    =>  json_decode($paypal['config'], true),
                            'transfer'  =>  json_decode($transfer['config'], true)
                        );
                        $bodyD['payments'] = array(
                            'sms'       =>  $payments['sms']['sms'],
                            'paypal'    =>  $payments['paypal'],
                            'transfer'  =>  $payments['transfer']['transfer']
                        );

                        $this->load->view('Service', $bodyD);
                    } else {
                        $this->load->view('errors/html/error_404.php');
                        return;
                    }
                }
            }
        }
    }

}