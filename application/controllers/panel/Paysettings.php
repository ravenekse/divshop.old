<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Paysettings extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $this->load->model('PaymentsM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Ustawienia";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        if($headerD['settings']['smsOperator'] == 0) {
            $bodyD['smsOperator'] = null;
        } else {
            $bodyD['smsOperator'] = $this->PaymentsM->get($headerD['settings']['smsOperator']);
            $bodyD['smsOperator']['config'] = json_decode($headerD['smsOperator']['config']);
        }
        $sms = $this->PaymentsM->get($headerD['settings']['smsOperator']);
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
        $bodyD['modules'] = $this->ModulesM->getAll();
        $this->load->view('panel/Paysettings', $bodyD);
    }

    public function savePayments() {

        $this->form_validation->set_rules('paymentsSmsOperator', 'paymentsSmsOperator', 'required|trim');
        if($this->input->post('paymentsSmsOperator') == 1) {
            $this->form_validation->set_rules('paymentsMicrosmsUserId', 'paymentsMicrosmsUserId', 'required|trim');
        }
        $this->form_validation->set_rules('paymentsPaypalAddress', 'paymentsPaypalAddress', 'trim');
        if($this->input->post('paymentsTransferShopId') != null || $this->input->post('paymentsTransferUserId') != null || $this->input->post('paymentsTransferHash') != null) {
            $this->form_validation->set_rules('paymentsTransferShopId', 'paymentsTransferShopId', 'required|trim');
            $this->form_validation->set_rules('paymentsTransferUserId', 'paymentsTransferUserId', 'required|trim');
            $this->form_validation->set_rules('paymentsTransferHash', 'paymentsTransferHash', 'required|trim');
        }

        if($this->form_validation->run() === TRUE) {

            $this->load->model('PaymentsM');

            $data['smsOperator'] = $this->input->post('paymentsSmsOperator');

            if(!$this->SettingsM->update($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił problem podczas łączenia się z bazą danych!";
                redirect(base_url('panel/paysettings'));
            }
            unset($data);


            if($this->input->post('paymentsSmsOperator') == 1) {
                $data['config'] = json_encode(array('sms' => array('userid' => $this->input->post('paymentsMicrosmsUserId'))));
            }
            if($this->input->post('paymentsSmsOperator') != null) {
                if(!$this->PaymentsM->update($this->input->post('paymentsSmsOperator'), $data)) {
                    $_SESSION['messageDangerSmall'] = "Wystąpił problem podczas łączenia się z bazą danych!";
                    redirect(base_url('panel/paysettings'));
                }
            }
            unset($data);

            $data['config'] = json_encode(array('address' => (($this->input->post('paymentsPaypalAddress') == null)) ? null : $this->input->post('paymentsPaypalAddress')));
            if(!$this->PaymentsM->update(2, $data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił problem podczas łączenia się z bazą danych!";
                redirect(base_url('panel/paysettings'));
            }
            unset($data);

            $data['config'] = json_encode(array('transfer' => array('shopid' => $this->input->post('paymentsTransferShopId'), 'userid' => $this->input->post('paymentsTransferUserId'), 'hash' => $this->input->post('paymentsTransferHash'))));
            if(!$this->PaymentsM->update(3, $data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił problem podczas łączenia się z bazą danych!";
                redirect(base_url('panel/paysettings'));
            }
            unset($data);


            $data['user'] = $_SESSION['name'];
            $data['section'] = "Ustawienia płatności";
            $data['details'] = "Użytkownik zmodyfikował ustawienia płatności";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessSmall'] = "Ustawienia płatności zostały pomyślnie zapisane!";
            redirect(base_url('panel/paysettings'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect(base_url('panel/paysettings'));
        }
    }
}