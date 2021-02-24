<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
		parent::__construct();	
    }

    public function index() {
        if($this->session->userdata('logged')){
            redirect($this->config->base_url('panel/dashboard')); 
        } else {
            sleep(1); 
            redirect($this->config->base_url('admin/auth'));
        }
    }

    public function auth() {
        if($this->session->userdata('logged')) redirect($this->config->base_url('panel/dashboard'));
        
        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Logowanie do ACP";
        $this->load->view('components/Header', $headerD);
        
        /**  Body section  */
        $this->load->view('Admin');
    }

    public function login() {
        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $api = new DIVShopAPI();
        $divsAPI = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsNewVersion'      =>   $api->get_latest_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        if($this->session->userdata('logged')) redirect($this->config->base_url('panel/dashboard'));
        
        /** Form settings */
        $this->form_validation->set_rules('authAdminLogin', 'Login administratora', 'required|trim');
        $this->form_validation->set_rules('authAdminPass', 'Hasło administratora', 'required|trim');

        if($this->form_validation->run() === TRUE) {

            $adminLogin = $this->input->post('authAdminLogin');
            $adminPass = $this->input->post('authAdminPass');

            $this->load->model('AdminsM');

            if(!$adminLogin = $this->AdminsM->getBy('name', $adminLogin)) {

                $data['lastIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
                if($data['lastIP'] == "::1") $data['lastIP'] = "127.0.0.1";
                $ipAddress = $data['lastIP'];
                $data['lastLogin'] = time();
                unset($data);

                $data['user'] = $this->input->post('authAdminLogin');
                $data['section'] = "Nieudane logowanie";
                $data['details'] = "Nieprawidłowy login";
                $data['ipAddress'] = $ipAddress;
                $data['time'] = time();

                $this->load->model('FailedLoginsM');
                $this->FailedLoginsM->add($data);

                $_SESSION['messageDangerSmall'] = "Podano nieprawidłowy login lub hasło!";
                redirect($this->config->base_url('admin/auth'));
            }
            if(!password_verify($adminPass, $adminLogin['password'])) {
                
                $data['lastIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
                if($data['lastIP'] == "::1") $data['lastIP'] = "127.0.0.1";
                $ipAddress = $data['lastIP'];
                $data['lastLogin'] = time();
                unset($data);

                $data['user'] = $this->input->post('authAdminLogin');
                $data['section'] = "Nieudane logowanie";
                $data['details'] = "Nieprawidłowe hasło";
                $data['ipAddress'] = $ipAddress;
                $data['time'] = time();

                $this->load->model('FailedLoginsM');
                $this->FailedLoginsM->add($data);

                $_SESSION['messageDangerSmall'] = "Podano nieprawidłowy login lub hasło!";
                redirect($this->config->base_url('admin/auth'));
            }

            $this->session->set_userdata('logged', TRUE);
            $domain = '.' . $_SERVER['HTTP_HOST'];
            set_cookie('isLogged', 'isLogged', 7200, $domain, '/', '', null, null);
            $this->session->set_userdata('name', $adminLogin['name']);
            $this->session->set_userdata('email', $adminLogin['email']);
            $this->session->set_userdata('avatar', $adminLogin['image']);

            $data['lastIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['lastIP'] == "::1") $data['lastIP'] = "127.0.0.1";
            $ipAddress = $data['lastIP'];
            $data['lastLogin'] = time();
            $data['browser'] = $_SERVER['HTTP_USER_AGENT'];

            $this->AdminsM->update($adminLogin['id'], $data);
            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Logowanie";
            $data['details'] = "Adres IP z którego się zalogowano: " . $ipAddress;
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();
            $this->LogsM->add($data);

            if($divsAPI['divsConnection']['status'] == true) {
                if($divsAPI['divsUpdate']['status']) {
                    $_SESSION['divsUpdateAvailable'] = 'Dostępna jest nowa wersja (' . $divsAPI['divsNewVersion']['latest_version'] . ') dla sklepu DIVShop.pro Standard Minecraft. Zalecamy jak najszybszą aktualizację';
                }
            }

            $_SESSION['messageSuccessSmall'] = "Pomyślnie zalogowano! &lt;br /&gt;Witaj w panelu administratora &lt;strong&gt;" . $adminLogin['name'] . "&lt;/strong&gt;!";
            redirect($this->config->base_url('panel/dashboard'));

        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza logowania!";
            redirect($this->config->base_url('admin/auth'));
        }
    }

    public function logout() {
        $this->session->unset_userdata(array('logged', 'name', 'email', 'avatar'));
        delete_cookie('isLogged');
        $_SESSION['messageSuccessSmall'] = "Pomyślnie wylogowano!";
        sleep(1);
        if(htmlspecialchars($_GET['fromLogout']) == "acp") {
            redirect($this->config->base_url('admin/auth'));
        } else {
            redirect($this->config->base_url());
        }
    }
}