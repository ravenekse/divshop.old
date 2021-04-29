<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Add extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {
        redirect($this->config->base_url('panel/dashboard'));
    }

    /**   Start Add Admin   */
    public function admin() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $api = new DIVShopAPI();
        $this->load->model('ModulesM');
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowy administrator";
		$this->load->view('components/Header', $headerD);


        /**  Body Section  */
        $this->load->view('panel/add/Admin', $bodyD);
    }
    public function adminCreate() {

        $this->form_validation->set_rules('adminName', 'adminName', 'required|trim');
        $this->form_validation->set_rules('adminEmail', 'adminEmail', 'trim');

        if($this->form_validation->run() === TRUE) {
            $data['name'] = $this->input->post('adminName');

            $this->load->model('AdminsM');

            if($this->AdminsM->getBy('name', $data['name'])) {
                $_SESSION['messageDangerSmall'] = "Administrator o takiej nazwie już istnieje!";
                redirect($this->config->base_url('panel/add/admin'));
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

            $password = random_string('alnum', 32);
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);

            $data['email'] = ($this->input->post('adminEmail') == null) ? null : $this->input->post('adminEmail');
            $adminName = $data['name'];

            if(!$this->AdminsM->add($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/add/admin'));
            }
            
            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Administratorzy";
            $data['details'] = "Użytkownik dodał <strong>administratora</strong> o nazwie <strong>" . $adminName . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie utworzono nowego użytkownika o nazwie &lt;strong&gt;" . $adminName ."&lt;/strong&gt;!";
            $_SESSION['newUserInfo'] = "Dane logowania do ACP dla nowego użytkownika:<br />Nazwa użytkownika: <strong>" . $adminName . "</strong><br /> Hasło: <strong>" . $password ."</strong><br /><br />Wyślij te dane jak najszybciej do nowego administratora, ponieważ znikną po przeładowaniu strony!";
            
            redirect($this->config->base_url('panel/admins'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/admin'));
        }
    }
    /**   End Add Admin   */
    
    /**   Start Add Server   */
    public function server() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowy serwer";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $this->load->view('panel/add/Server', $bodyD);
    }
    public function serverCreate() {

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
            $serverShowPort = $this->input->post('serverShowPort');
            $data['name'] = $this->input->post('serverName');

            $this->load->model('ServersM');

            if($this->ServersM->getBy('name', $data['name'])) {
                $_SESSION['messageDangerSmall'] = "Serwer o takiej nazwie już istnieje!";
                redirect($this->config->base_url('panel/add/server'));
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

            if(!$this->ServersM->add($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/add/server'));
            }
            
            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Serwery";
            $data['details'] = "Użytkownik dodał <strong>serwer</strong> o nazwie <strong>" . $serverName . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie dodano serwer o nazwie &lt;strong&gt;" . $serverName ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/servers'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/server'));
        }
    }
    /**   End Add Server   */
    
    /**   Start Add Service   */
    public function service() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ServersM');
        $this->load->model('ModulesM');
        $this->load->model('PaymentsM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowa usługa";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $bodyD['servers'] = $this->ServersM->getAll();
        $bodyD['payments'] = array(
            'paypal'    =>  $this->PaymentsM->get(2),
            'transfer'  =>  $this->PaymentsM->get(3)
        );
        $this->load->view('panel/add/Service', $bodyD);
    }
    public function serviceCreate() {

        $settings = $this->SettingsM->getSettings();
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
            $serviceActive = $this->input->post('serviceActive');
            $data['name'] = $this->input->post('serviceName');

            $this->load->model('ServicesM');

            if($this->ServicesM->getBy('name', $data['name'])) {
                $_SESSION['messageDangerSmall'] = "Usługa o takiej nazwie już istnieje!";
                redirect($this->config->base_url('panel/add/service'));
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

            if(!$this->ServicesM->add($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/add/service'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Serwery";
            $data['details'] = "Użytkownik dodał <strong>usługę</strong> o nazwie <strong>" . $serviceName . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie dodano usługę o nazwie &lt;strong&gt;" . $serviceName . "&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/services'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/service'));
        }
    }
    /**   End Add Service   */

    /**   Start Add Voucher   */
    public function voucher() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ServicesM');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowy voucher";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $bodyD['services'] = $this->ServicesM->getAll();

        $this->load->view('panel/add/Voucher', $bodyD);
    }
    public function voucherCreate() {

        $this->load->model('ServicesM');
        $this->load->model('VouchersM');
        $settings = $this->SettingsM->getSettings();
        $this->form_validation->set_rules('voucherService', 'voucherService', 'required|trim');
        $this->form_validation->set_rules('voucherAmount', 'voucherAmount', 'required|trim');

        if($this->form_validation->run() === TRUE) {

            $data['service'] = $this->input->post('voucherService');
            $voucherAmount = $this->input->post('voucherAmount');
            $vouchers = array();
            $data['generated'] = time();

            if(!$service = $this->ServicesM->getBy('id', $data['service'])) {
                $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                redirect($this->config->base_url('panel/add/voucher'));
            }

            if($voucherAmount > 20) {
                for($i=1; $i <= 20 ; $i++) { 
                    $data['code'] = $settings['voucherPrfx'] . random_string('alpha', $settings['voucherLength']);
                    array_push($vouchers, $data);
                }
            } elseif($voucherAmount <= 20) {
                for($i=1; $i <= $voucherAmount ; $i++) { 
                    $data['code'] = $settings['voucherPrfx'] . random_string('alpha', $settings['voucherLength']);
                    array_push($vouchers, $data);
                }
            }

            if(!$this->VouchersM->addMultiple($vouchers)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                redirect($this->config->base_url('panel/add/voucher'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Vouchery";
            $data['details'] = "Użytkownik wygenerował <strong>" . count($vouchers) . "</strong> voucherów dla usługi <strong>" . $service['name'] . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie wygenerowano &lt;strong&gt;" . count($vouchers) ."&lt;/strong&gt; voucherów dla usługi &lt;strong&gt;" . $service['name'] ."&lt;/strong&gt;!";
            redirect($this->config->base_url('panel/vouchers'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/voucher'));
        }
    }
    /**   End Add Voucher   */

    /**   Start Add News   */
    public function news() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowy news";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $this->load->view('panel/add/News', $bodyD);
    }
    public function newsCreate() {

        $this->form_validation->set_rules('newsTitle', 'newsTitle', 'required|trim');
        $this->form_validation->set_rules('newsContent', 'newsContent', 'required|trim');
        $this->form_validation->set_rules('newsActive', 'newsActive', 'trim');

        if($this->form_validation->run() === TRUE) {
            $newsActive = $this->input->post('newsActive');
            $data['title'] = $this->input->post('newsTitle');

            $this->load->model('NewsM');

            if($this->NewsM->getBy('title', $data['title'])) {
                $_SESSION['messageDangerSmall'] = "News o takiej nazwie już istnieje!";
                redirect($this->config->base_url('panel/add/news'));
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
            $data['date'] = time();

            if((isset($newsActive)) && ($newsActive != null)) $newsActive = true; else $newsActive = false;
            $data['newsActive'] = $newsActive;
            $newsTitle = $data['title'];

            if(!$this->NewsM->add($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/add/news'));
            }
            
            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Aktualności";
            $data['details'] = "Użytkownik dodał <strong>newsa</strong> o tytule <strong>" . $newsTitle . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie dodano newsa o tytule &lt;strong&gt;" . $newsTitle ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/news'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/news'));
        }
    }
    /**   End Add News   */

    /**   Start Add Page   */
    public function page() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );
        $bodyD['modules'] = $this->ModulesM->getAll();

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nowa strona";
		$this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $this->load->view('panel/add/Page', $bodyD);
    }
    public function pageCreate() {

        $this->form_validation->set_rules('pageTitle', 'pageTitle', 'required|trim');
        $this->form_validation->set_rules('pageIcon', 'pageIcon', 'trim');
        $this->form_validation->set_rules('pageLink', 'pageLink', 'trim');
        $this->form_validation->set_rules('pageContent', 'pageContent', 'trim');
        $this->form_validation->set_rules('pageActive', 'pageActive', 'trim');

        if($this->form_validation->run() === TRUE) {
            $pageActive = $this->input->post('pageActive');
            $data['title'] = $this->input->post('pageTitle');

            if(!preg_match("/^[a-zA-Z0-9 ]{1,255}[^\s]$/", $data['title'])) {
                $_SESSION['messageDangerSmall'] = "Nazwa strony zawiera niedozwolone znaki!";
                redirect($this->config->base_url('panel/add/page'));
            }

            $this->load->model('PagesM');

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

            if(!$this->PagesM->add($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                redirect($this->config->base_url('panel/add/page'));
            }
            
            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Kreator | Aktualności";
            $data['details'] = "Użytkownik dodał <strong>" . $type . "</strong> o nazwie <strong>" . $pageTitle . "</strong>";
            $data['logIP'] = $this->input->ip_address();
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessBig'] = "Pomyślnie dodano " . $type . " o nazwie &lt;strong&gt;" . $pageTitle ."&lt;/strong&gt;!";
            
            redirect($this->config->base_url('panel/pages'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect($this->config->base_url('panel/add/page'));
        }
    }
    /**   End Add Page   */
}