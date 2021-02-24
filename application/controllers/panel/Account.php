<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $this->load->model('AdminsM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

		/**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Konto";
		$this->load->view('components/Header', $headerD);

        /**  Body section  */
        $bodyD['modules'] = $this->ModulesM->getAll();
        $bodyD['admin'] = $this->AdminsM->getBy('name', $this->session->userdata('name'));
        $this->load->view('panel/Account', $bodyD);
    }

    public function saveAccount() {

        $this->form_validation->set_rules('adminName', 'adminName', 'required|trim');
        $this->form_validation->set_rules('adminEmail', 'adminEmail', 'trim');
        if($this->input->post('adminPassOld') == null && $this->input->post('adminPassNew1') == null){
            $this->form_validation->set_rules('adminPassOld', 'adminPassOld', 'trim');
            $this->form_validation->set_rules('adminPassNew1', 'adminPassNew1', 'trim');
            $this->form_validation->set_rules('adminPassNew2', 'adminPassNew2', 'trim');
        } else {
            $this->form_validation->set_rules('adminPassOld', 'adminPassOld', 'required|trim');
            $this->form_validation->set_rules('adminPassNew1', 'adminPassNew1', 'required|trim');
            $this->form_validation->set_rules('adminPassNew2', 'adminPassNew2', 'required|trim');
        }

        if($this->form_validation->run() === TRUE) {
            $adminPassOld = $this->input->post('adminPassOld');
            $adminPassNew1 = $this->input->post('adminPassNew1');
            $adminPassNew2 = $this->input->post('adminPassNew2');

            $this->load->model('AdminsM');

            $data['name'] = $this->input->post('adminName');

            if(!$admin = $this->AdminsM->getBy('name', $this->session->userdata('name'))) {
                $_SESSION['messageDangerSmall'] = "Coś poszło nie tak, spróbuj jeszcze raz!";
                redirect($this->config->base_url('panel/account'));
            }

            if($this->input->post('adminPassOld') != null){
                if(!password_verify($adminPassOld, $admin['password'])) {
                    $_SESSION['messageDangerSmall'] = "Podane aktualne hasło jest nieprawidłowe!";
                    redirect(base_url('panel/account'));
                }
            }
            if($adminPassNew1 != $adminPassNew2) {
                $_SESSION['messageDangerSmall'] = "Podane nowe hasła nie są takie same!";
                redirect(base_url('panel/account'));
            }
            if($this->input->post('adminPassOld') != null){
                $data['password'] = password_hash($adminPassNew1, PASSWORD_DEFAULT);
            }

            $config['upload_path'] = './assets/uploads/admins';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('adminImage')) {
                $uploadData = $this->upload->data();
                $data['image'] = base_url('assets/uploads/admins/' . $uploadData['file_name']);
                $this->session->unset_userdata('avatar');
                $this->session->set_userdata('avatar', $data['image']);
            }
            if($this->input->post('adminImage_remove')) {
                $data['image'] = null;
                $this->session->unset_userdata('avatar');
                $this->session->set_userdata('avatar', null);
            }

            if($this->input->post('adminEmail') == null) {
                $data['email'] = null;
            } else {
                $data['email'] = $this->input->post('adminEmail');
                $this->session->unset_userdata('email');
                $this->session->set_userdata('email', $data['email']);
            }

            $adminName = $data['name'];

            if(!$this->AdminsM->update($admin['id'], $data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/account'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Administratorzy";
            $data['details'] = "Użytkownik edytował ustawienia <strong>swojego konta</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            if($adminName !== $_SESSION['name']){
                $this->session->unset_userdata(array('logged', 'name', 'email', 'avatar'));
                delete_cookie('isLogged');
                $_SESSION['messageSuccessSmall'] = "Wylogowano przez zmianę nazwy!";
                sleep(1);
                redirect($this->config->base_url('admin/auth'));
            }

            $_SESSION['messageSuccessSmall'] = "Ustawienia konta zostały pomyślnie zmienione!";
            
            redirect($this->config->base_url('panel/account'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/account'));
        }
    }
}