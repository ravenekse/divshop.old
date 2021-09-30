<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

class Updates extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged')) {
            redirect($this->config->base_url('admin/auth'));
        }
    }

    public function index()
    {
        require_once APPPATH.'libraries/divshop-api/divsAPI.php';
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = [
            'divsVersion'      => $api->get_current_version(),
            'divsUpdate'       => $api->check_update(),
        ];

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
        $headerD['pageTitle'] = $headerD['settings']['pageTitle'].' | Aktualizator';
        $this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $this->form_validation->set_rules('update_id', 'update_id', 'trim');
        $this->form_validation->set_rules('has_sql', 'has_sql', 'trim');
        $this->form_validation->set_rules('version', 'version', 'trim');

        if ($this->form_validation->run() === true) {
            $bodyD['updateInfo'] = [
                'show_loader' => true,
                'api'         => $api,
                'update_id'   => strip_tags($this->input->post('update_id')),
                'has_sql'     => strip_tags($this->input->post('has_sql')),
                'version'     => strip_tags($this->input->post('version')),
            ];
            $data['user'] = $_SESSION['name'];
            $data['section'] = 'Aktualizator';
            $data['details'] = 'Użytkownik zaktualizował sklep';
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);
            unset($data);
        } else {
            $bodyD['updateInfo'] = [
                'show_loader' => false,
                'api'         => $api,
                'update_id'   => null,
                'has_sql'     => null,
                'version'     => null,
            ];
        }

        $bodyD['modules'] = $this->ModulesM->getAll();
        $this->load->view('panel/Updates', $bodyD);
    }
}
