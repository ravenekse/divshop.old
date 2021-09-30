<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

class Modules extends CI_Controller
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
        $headerD['pageTitle'] = $headerD['settings']['pageTitle'].' | Moduły';
        $this->load->view('components/Header', $headerD);

        /** Body section */
        $bodyD['modules'] = $this->ModulesM->getAll();
        $this->load->view('panel/Modules', $bodyD);
    }

    public function changeModuleStatus()
    {
        $this->form_validation->set_rules('moduleId', 'moduleId', 'required|trim');
        $this->form_validation->set_rules('moduleStatus', 'moduleStatus', 'trim');

        if ($this->form_validation->run() === true) {
            $this->load->model('ModulesM');

            $moduleId = $this->input->post('moduleId');
            $moduleStatus = $this->input->post('moduleStatus');

            if (!$module = $this->ModulesM->getBy('id', $moduleId)) {
                $_SESSION['messageDangerSmall'] = 'Wystąpił błąd podczas łączenia z bazą danych!';
                redirect($this->config->base_url('panel/modules'));
            }

            if ((isset($moduleStatus)) && ($moduleStatus != null)) {
                $moduleStatus = true;
            } else {
                $moduleStatus = false;
            }

            $moduleName = $module['moduleName'];
            $data['moduleEnabled'] = $moduleStatus;

            if (!$this->ModulesM->update($moduleId, $data)) {
                $_SESSION['messageDangerSmall'] = 'Wystąpił błąd podczas łączenia z bazą danych!';
                redirect($this->config->base_url('panel/modules'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = 'Moduły';
            if ($moduleStatus == true) {
                $data['details'] = 'Użytkownik aktywował moduł <strong>'.getModuleName($moduleName).'</strong>';
            } else {
                $data['details'] = 'Użytkownik dezaktywował moduł <strong>'.getModuleName($moduleName).'</strong>';
            }
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();
            $this->LogsM->add($data);

            if ($moduleStatus == true) {
                $_SESSION['messageSuccessSmall'] = 'Pomyślnie aktywowano moduł '.getModuleName($moduleName);
                redirect($this->config->base_url('panel/modules'));
            } else {
                $_SESSION['messageSuccessSmall'] = 'Pomyślnie dezaktywowano moduł '.getModuleName($moduleName);
                redirect($this->config->base_url('panel/modules'));
            }
        } else {
            $_SESSION['messageDangerSmall'] = 'Coś poszło nie tak, spróbuj jeszcze raz!';
            redirect($this->config->base_url('panel/modules'));
        }
    }
}
