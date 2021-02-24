<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Edit extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {
        redirect($this->config->base_url('panel/dashboard'));
    }

    /**   Start Edit Admin   */
    public function admin() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        $adminId = $this->input->post('adminId');

        if($adminId == null) {
            $this->load->view('errors/html/error_404.php');
            log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 4) . "] No POST argument given!");
            return;
        } else {
            $this->load->model('AdminsM');

            if(!$bodyD['admin'] = $this->AdminsM->getBy('id', $adminId, false)) {
                $this->load->view('errors/html/error_404.php');
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 3) . "] Admin with ID #" . $adminId . " does not exist in database!");
                return;
            }

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Edycja administratora " . $bodyD['admin']['name'] . " (ID #" . $bodyD['admin']['id'] . ")";
            $this->load->view('components/Header', $headerD);

            /**  Body Section  */
            $bodyD['modules'] = $this->ModulesM->getAll();
            $this->load->view('panel/edit/Admin', $bodyD);
        }
    }
    public function adminSave() {

        $this->form_validation->set_rules('adminId', 'adminId', 'required|trim');
        $this->form_validation->set_rules('adminName', 'adminName', 'required|trim');
        $this->form_validation->set_rules('adminEmail', 'adminEmail', 'trim');
        if($this->input->post('adminPassNew1') == null){
            $this->form_validation->set_rules('adminPassNew1', 'adminPassNew1', 'trim');
            $this->form_validation->set_rules('adminPassNew2', 'adminPassNew2', 'trim');
        } else {
            $this->form_validation->set_rules('adminPassNew1', 'adminPassNew1', 'required|trim');
            $this->form_validation->set_rules('adminPassNew2', 'adminPassNew2', 'required|trim');
        }

        if($this->form_validation->run() === TRUE) {
            $adminId = $this->input->post('adminId');
            $passNew1 = $this->input->post('adminPassNew1');
            $passNew2 = $this->input->post('adminPassNew2');
            $data['name'] = $this->input->post('adminName');

            $this->load->model('AdminsM');

            if($adminId != '1') {
                if(!$this->AdminsM->getBy('id', $adminId)) {
                    $_SESSION['messageDangerSmall'] = "Edycja administratora nie powiodła się! Ten administrator nie istnieje w bazie danych!";
                    redirect($this->config->base_url('panel/admins'));
                }
            } else {
                $_SESSION['messageDangerSmall'] = "Coś poszło nie tak, spróbuj jeszcze raz!";
                redirect($this->config->base_url('panel/admins'));
            }

            $config['upload_path'] = './assets/uploads/admins';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('adminImage')) {
                $uploadData = $this->upload->data();
                $data['image'] = base_url('assets/uploads/admins/' . $uploadData['file_name']);
            }

            if($this->input->post('adminPassNew1')){
                if($passNew1 != $passNew2) {
                    $_SESSION['messageDangerSmall'] = "Podane hasła nie pasują do siebie!";
                    redirect($this->config->base_url('panel/admins'));
                }
                $data['password'] = password_hash($passNew1, PASSWORD_DEFAULT);
            }
            
            $data['email'] = ($this->input->post('adminEmail') == null) ? null : $this->input->post('adminEmail');
            $adminName = $data['name'];

            if(!$this->AdminsM->update($adminId, $data)) {
                $_SESSION['messageDangerSmall'] = "Edycja administratora nie powiodła się! Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/admins'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Edytor | Administratorzy";
            $data['details'] = "Użytkownik edytował <strong>administratora</strong> o nazwie <strong>" . $adminName . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie edytowano administratora o nazwie &lt;strong&gt;" . $adminName ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/admins'));
        } else {
            $_SESSION['messageDangerSmall'] = "Edycja administratora nie powiodła się! Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/admins'));
        }
    }
    /**   End Edit Admin   */

    /**   Start Edit Server   */
    public function server() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        $serverId = $this->input->post('serverId');

        if($serverId == null) {
            $this->load->view('errors/html/error_404.php');
            log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 4) . "] No POST argument given!");
            return;
        } else {
            $this->load->model('ServersM');

            if(!$bodyD['server'] = $this->ServersM->getBy('id', $serverId, false)) {
                $this->load->view('errors/html/error_404.php');
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 3) . "] Server with ID #" . $serverId . " does not exist in database!");
                return;
            }

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Edycja serwera " . $bodyD['server']['name'] . " (ID #" . $bodyD['server']['id'] . ")";
            $this->load->view('components/Header', $headerD);

            /**  Body Section  */
            $bodyD['modules'] = $this->ModulesM->getAll();
            $this->load->view('panel/edit/Server', $bodyD);
        }
    }
    public function serverSave() {

        $this->form_validation->set_rules('serverId', 'serverId', 'required|trim');
        $this->form_validation->set_rules('serverName', 'serverName', 'required|trim');
        $this->form_validation->set_rules('serverIpAddress', 'serverIpAddress', 'required|trim');
        $this->form_validation->set_rules('serverPort', 'serverPort', 'required|trim');
        if($this->input->post('otherServerVersion') != null) {
            $this->form_validation->set_rules('serverVersion', 'serverVersion', 'required|trim');
        }
        $this->form_validation->set_rules('serverRconPass', 'serverRconPass', 'required|trim');
        $this->form_validation->set_rules('serverRconPort', 'serverRconPort', 'required|trim');
        $this->form_validation->set_rules('serverShowPort', 'serverShowPort', 'trim');

        if($this->form_validation->run() === TRUE) {
            $serverId = $this->input->post('serverId');
            $serverShowPort = $this->input->post('serverShowPort');
            $data['name'] = $this->input->post('serverName');

            $this->load->model('ServersM');

            if(!$this->ServersM->getBy('id', $serverId)) {
                $_SESSION['messageDangerSmall'] = "Edycja serwera nie powiodła się! Ten serwer nie istnieje w bazie danych!";
                redirect($this->config->base_url('panel/servers'));
            }

            $config['upload_path'] = './assets/uploads/servers';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('serverImage')) {
                $uploadData = $this->upload->data();
                $data['image'] = base_url('assets/uploads/servers/' . $uploadData['file_name']);
            }
            
            $data['ip'] = $this->input->post('serverIpAddress');
            $data['port'] = $this->input->post('serverPort');
            if($this->input->post('otherServerVersion') != null) {
                $data['serverVersion'] = $this->input->post('serverVersion');
            } else {
                $data['serverVersion'] = null;
            }
            $data['rconPass'] = $this->input->post('serverRconPass');
            $data['rconPort'] = $this->input->post('serverRconPort');
            if((isset($serverShowPort)) && ($serverShowPort != null)) $serverShowPort = true; else $serverShowPort = false;
            $data['showPort'] = $serverShowPort;
            $serverName = $data['name'];

            if(!$this->ServersM->update($serverId, $data)) {
                $_SESSION['messageDangerSmall'] = "Edycja serwera nie powiodła się! Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/servers'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Edytor | Serwery";
            $data['details'] = "Użytkownik edytował <strong>serwer</strong> o nazwie <strong>" . $serverName . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie edytowano serwer o nazwie &lt;strong&gt;" . $serverName ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/servers'));
        } else {
            $_SESSION['messageDangerSmall'] = "Edycja serwera nie powiodła się! Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/servers'));
        }
    }
    /**   End Edit Server   */
    
    /**   Start Edit Service   */
    public function service() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        $serviceId = $this->input->post('serviceId');

        if($serviceId == null) {
            $this->load->view('errors/html/error_404.php');
            log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 4) . "] No POST argument given!");
            return;
        } else {
            $this->load->model('ServicesM');
            $this->load->model('ServersM');
            $this->load->model('PaymentsM');

            if(!$bodyD['service'] = $this->ServicesM->getBy('id', $serviceId, false)) {
                $this->load->view('errors/html/error_404.php');
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 3) . "] Service with ID #" . $serviceId . " does not exist in database!");
                return;
            }

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Edycja usługi " . $bodyD['service']['name'] . " (ID #" . $bodyD['service']['id'] . ")";
            if($headerD['settings']['smsOperator'] == 0) {
                $headerD['smsOperator'] = null;
            } else {
                $headerD['smsOperator'] = $this->PaymentsM->get($headerD['settings']['smsOperator']);
                $headerD['smsOperator']['config'] = json_decode($headerD['smsOperator']['config']);
            }
            $this->load->view('components/Header', $headerD);

            /**  Body Section  */
            $servers = $this->ServersM->getAll();
            $services = $this->ServicesM->getAll();
            $bodyD['modules'] = $this->ModulesM->getAll();
            $bodyD['servers'] = $servers;
            if($bodyD['service']['smsConfig'] != null) {
                $bodyD['service']['smsConfig'] = json_decode($bodyD['service']['smsConfig'], true);
            }
            $bodyD['services'] = $this->ServicesM->getAll();
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
            $bodyD['payments'] = array(
                'paypal'    =>  $this->PaymentsM->get(2),
                'transfer'  =>  $this->PaymentsM->get(3)
            );
            $this->load->view('panel/edit/Service', $bodyD);
        }
    }
    public function serviceSave() {

        $settings = $this->SettingsM->getSettings();
        $this->form_validation->set_rules('serviceId');
        $this->form_validation->set_rules('serviceName', 'serviceName', 'required|trim');
        $this->form_validation->set_rules('serviceDescription', 'serviceDescription', 'trim');
        $this->form_validation->set_rules('serviceServer', 'serviceServer', 'required|trim');
        if($settings['smsOperator'] != 0 && $settings['smsOperator'] = 1) {
            $this->form_validation->set_rules('serviceSmsChannel', 'serviceSmsChannel', 'required|trim');
            $this->form_validation->set_rules('serviceSmsChannelId', 'serviceSmsChannelId', 'required|trim');
            $this->form_validation->set_rules('serviceSmsNumber', 'serviceSmsNumber', 'required|trim');
        }
        $this->form_validation->set_rules('servicePaypalCost', 'servicePaypalCost', 'trim');
        $this->form_validation->set_rules('serviceTransferCost', 'serviceTransferCost', 'trim');
        $this->form_validation->set_rules('serviceCommands', 'serviceCommands', 'required|trim');
        $this->form_validation->set_rules('serviceActive', 'serviceActive', 'trim');

        if($this->form_validation->run() === TRUE) {
            $serviceId = $this->input->post('serviceId');
            $serviceActive = $this->input->post('serviceActive');
            $data['name'] = $this->input->post('serviceName');

            $this->load->model('ServicesM');

            if(!$this->ServicesM->getBy('id', $serviceId)) {
                $_SESSION['messageDangerSmall'] = "Edycja usługi nie powiodła się! Ta usługa nie istnieje w bazie danych!";
                redirect($this->config->base_url('panel/services'));
            }

            $config['upload_path'] = './assets/uploads/services';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('serviceImage')) {
                $uploadData = $this->upload->data();
                $data['image'] = base_url('assets/uploads/services/' . $uploadData['file_name']);
            }

            $data['description'] = ($this->input->post('serviceDescription') == null) ? null : $this->input->post('serviceDescription');
            $data['server'] = $this->input->post('serviceServer');
            if($settings['smsOperator'] != 0 && $settings['smsOperator'] = 1) {
                $smsConfig = array(
                    'operator'   => $settings['smsOperator'],
                    'smsNumber'  => $this->input->post('serviceSmsNumber')
                );
                $smsConfig['smsChannel'] = $this->input->post('serviceSmsChannel');
                $smsConfig['smsChannelId'] = $this->input->post('serviceSmsChannelId');
            }
            if($smsConfig['smsNumber'] == null && $smsConfig['smsChannel'] && $smsConfig['smsChannelId'] == null) {
                $data['smsConfig'] = null;
            } else {
                $data['smsConfig'] = json_encode($smsConfig);
            }
            $data['paypalCost'] = ($this->input->post('servicePaypalCost') == null) ? null : $this->input->post('servicePaypalCost');
            $data['transferCost'] = ($this->input->post('serviceTransferCost') == null) ? null : $this->input->post('serviceTransferCost');
            $data['commands'] = $this->input->post('serviceCommands');
            if((isset($serviceActive)) && ($serviceActive != null)) $serviceActive = true; else $serviceActive = false;
            $data['serviceActive'] = $serviceActive;
            $serviceName = $data['name'];

            if(!$this->ServicesM->update($serviceId, $data)) {
                $_SESSION['messageDangerSmall'] = "Edycja usługi nie powiodła się! Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/services'));
            }

            unset($data);
            
            $data['user'] = $_SESSION['name'];
            $data['section'] = "Edytor | Usługi";
            $data['details'] = "Użytkownik edytował <strong>usługę</strong> o nazwie <strong>" . $serviceName . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie edytowano usługę o nazwie &lt;strong&gt;" . $serviceName ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/services'));
        } else {
            $_SESSION['messageDangerSmall'] = "Edycja usługi nie powiodła się! Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/services'));
        }
    }
    /**   End Edit Service   */

    /**   Start Edit News   */
    public function news() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        $newsId = $this->input->post('newsId');

        if($newsId == null) {
            $this->load->view('errors/html/error_404.php');
            log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 4) . "] No POST argument given!");
            return;
        } else {
            $this->load->model('NewsM');

            if(!$bodyD['news'] = $this->NewsM->getBy('id', $newsId, false)) {
                $this->load->view('errors/html/error_404.php');
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 3) . "] News with ID #" . $newsId . " does not exist in database!");
                return;
            }

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Edycja newsa " . $bodyD['news']['title'] . " (ID #" . $bodyD['news']['id'] . ")";
            $this->load->view('components/Header', $headerD);

            /**  Body Section  */
            $bodyD['modules'] = $this->ModulesM->getAll();
            $this->load->view('panel/edit/News', $bodyD);
        }
    }
    public function newsSave() {

        $this->form_validation->set_rules('newsId');
        $this->form_validation->set_rules('newsTitle', 'newsTitle', 'required|trim');
        $this->form_validation->set_rules('newsContent', 'newsContent', 'required|trim');
        $this->form_validation->set_rules('newsActive', 'newsActive', 'trim');

        if($this->form_validation->run() === TRUE) {
            $newsId = $this->input->post('newsId');
            $newsActive = $this->input->post('newsActive');
            $data['title'] = $this->input->post('newsTitle');

            $this->load->model('NewsM');

            if(!$this->NewsM->getBy('id', $newsId)) {
                $_SESSION['messageDangerSmall'] = "Edycja newsa nie powiodła się! Ten news nie istnieje w bazie danych!";
                redirect($this->config->base_url('panel/news'));
            }

            $config['upload_path'] = './assets/uploads/news';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if($this->upload->do_upload('newsImage')) {
                $uploadData = $this->upload->data();
                $data['image'] = base_url('assets/uploads/news/' . $uploadData['file_name']);
            }

            $data['content'] = $this->input->post('newsContent');

            if((isset($newsActive)) && ($newsActive != null)) $newsActive = true; else $newsActive = false;
            $data['newsActive'] = $newsActive;
            $newsTitle = $data['title'];

            if(!$this->NewsM->update($newsId, $data)) {
                $_SESSION['messageDangerSmall'] = "Edycja newsa nie powiodła się! Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/news'));
            }

            unset($data);
            
            $data['user'] = $_SESSION['name'];
            $data['section'] = "Edytor | Newsy";
            $data['details'] = "Użytkownik edytował <strong>newsa</strong> o tytule <strong>" . $newsTitle . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie edytowano newsa o tytule &lt;strong&gt;" . $newsTitle ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/news'));
        } else {
            $_SESSION['messageDangerSmall'] = "Edycja strony nie powiodła się! Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/news'));
        }
    }
    /**   End Edit News   */
    
    /**   Start Edit Page   */
    public function page() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsConnection'   =>   $api->check_connection(),
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        $pageId = $this->input->post('pageId');

        if($pageId == null) {
            $this->load->view('errors/html/error_404.php');
            log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 4) . "] No POST argument given!");
            return;
        } else {
            $this->load->model('PagesM');

            if(!$bodyD['page'] = $this->PagesM->getBy('id', $pageId, false)) {
                $this->load->view('errors/html/error_404.php');
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 3) . "] Page with ID #" . $pageId . " does not exist in database!");
                return;
            }

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Edycja strony " . $bodyD['page']['title'] . " (ID #" . $bodyD['page']['id'] . ")";
            $this->load->view('components/Header', $headerD);

            /**  Body Section  */
            $bodyD['modules'] = $this->ModulesM->getAll();
            $this->load->view('panel/edit/Page', $bodyD);
        }
    }
    public function pageSave() {

        $this->form_validation->set_rules('pageId');
        $this->form_validation->set_rules('pageTitle', 'pageTitle', 'required|trim');
        $this->form_validation->set_rules('pageIcon', 'pageIcon', 'trim');
        $this->form_validation->set_rules('pageLink', 'pageLink', 'trim');
        $this->form_validation->set_rules('pageContent', 'pageContent', 'required|trim');
        $this->form_validation->set_rules('pageActive', 'pageActive', 'trim');

        if($this->form_validation->run() === TRUE) {
            $pageId = $this->input->post('pageId');
            $pageActive = $this->input->post('pageActive');
            $data['title'] = $this->input->post('pageTitle');

            $this->load->model('PagesM');

            if(!$this->PagesM->getBy('id', $pageId)) {
                $_SESSION['messageDangerSmall'] = "Edycja strony nie powiodła się! Ta strona nie istnieje w bazie danych!";
                redirect($this->config->base_url('panel/pages'));
            }

            if(!preg_match("/^[a-zA-Z0-9 ]{1,255}[^\s]$/", $data['title'])) {
                $_SESSION['messageDangerSmall'] = "Nazwa strony zawiera niedozwolone znaki!";
                redirect($this->config->base_url('panel/pages'));
            }

            $data['link'] = ($this->input->post('pageTitle') == null) ? null : $this->input->post('pageLink');
            $data['icon'] = ($this->input->post('pageIcon') == null) ? null : $this->input->post('pageIcon');
            $data['content'] = ($this->input->post('pageContent') == null) ? null : $this->input->post('pageContent');

            if($data['link'] != null) {
                $type = "odnośnik";
            } else {
                $type = "stronę";
            }

            if((isset($pageActive)) && ($pageActive != null)) $pageActive = true; else $pageActive = false;
            $data['pageActive'] = $pageActive;
            $pageTitle = $data['title'];

            if(!$this->PagesM->update($pageId, $data)) {
                $_SESSION['messageDangerSmall'] = "Edycja strony nie powiodła się! Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/pages'));
            }

            unset($data);
            
            $data['user'] = $_SESSION['name'];
            $data['section'] = "Edytor | Strony";
            $data['details'] = "Użytkownik edytował <strong>" . $type . "</strong> o nazwie <strong>" . $pageTitle . "</strong>";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie edytowano " . $type . " o nazwie &lt;strong&gt;" . $pageTitle ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/pages'));
        } else {
            $_SESSION['messageDangerSmall'] = "Edycja strony nie powiodła się! Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/pages'));
        }
    }
    /**   End Edit Page   */
}