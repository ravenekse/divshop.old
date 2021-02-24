<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class Payments extends CI_Controller {
    public function __construct() {
		parent::__construct();
    }
    
    public function index() {
        $this->load->view('errors/html/error_404.php');
    }

    public function sms() {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!empty($_POST)) {

                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
                $this->load->model('ServicesM');
                $this->load->model('ServersM');
                $this->load->model('PaymentsM');
                $this->load->model('PurchasesM');
                $this->load->helper('rcon_helper');
                $settings = $this->SettingsM->getSettings();
                
                $this->form_validation->set_rules('nickName', 'nickName', 'required|trim');
                $this->form_validation->set_rules('smsCode', 'smsCode', 'required|trim|max_length[8]');
                $this->form_validation->set_rules('serviceId', 'serviceId', 'required|trim');
                $this->form_validation->set_rules('serviceName', 'serviceName', 'required|trim');
                $this->form_validation->set_rules('serverId', 'serverId', 'required|trim');
                $this->form_validation->set_rules('serverName', 'serverId', 'required|trim');
                
                $nickName = $this->input->post('nickName');
                $smsCode = $this->input->post('smsCode');
                $serviceId = $this->input->post('serviceId');
                $serviceName = $this->input->post('serviceName');
                $serverId = $this->input->post('serverId');
                $serverName = $this->input->post('serverName');

                if($this->form_validation->run() === TRUE) {

                    if($this->input->post('acceptRules') == null) {
                        $_SESSION['messageDangerSmall'] = "Zaakceptowanie regulaminu płatności jest wymagane!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    if(!$service = $this->ServicesM->getBy('id', $serviceId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                    if(!$server = $this->ServersM->getBy('id', $serverId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    try {
                        $query = new MinecraftPing($server['ip'], $server['port']);
                        $servers['status']['online'] = true;
                    } catch(MinecraftPingException $e) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
                        $_SESSION['messageDangerSmall'] = "Serwer, na którym próbujesz zakupić usługę jest aktualnie wyłączony";
                        redirect($this->config->base_url('shop'));
                    } finally {
                        if (isset($query)) $query->Close();
                    }

                    $sms = $this->PaymentsM->get(1);
                    $payments = array(
                        'sms'       =>  json_decode($sms['config'], true),
                    );
                    $payments = array(
                        'sms'       =>  $payments['sms']['sms'],
                    );
                    if($settings['smsOperator'] == 0 || $payments['sms']['userid'] == null) {
                        $_SESSION['messageDangerSmall'] = "Ustawienia płatności SMS Premium nie zostały skonfigurowane!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                    $service['smsConfig'] = json_decode($service['smsConfig'], true);

                    $serverData = array(
                        'ipAddress'     =>  $server['ip'],
                        'rconPort'      =>  $server['rconPort'],
                        'rconPassword'  =>  $server['rconPass']
                    );

                    $rconConnecion = checkRconConnection($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword']);

                    if($rconConnecion['status'] == true) {
                        if($settings['smsOperator'] == 1) {
                            $this->load->helper('payments/microsms_helper');
                            $smsResponse = checkCode($payments['sms']['userid'], $service['smsConfig']['smsChannelId'], $service['smsConfig']['smsNumber'], $smsCode);

                            if($smsResponse['status'] == true) {
                                $serverCommands = explode('; ', $service['commands']);
                                $rconResponse = sendRconCommand($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword'], $serverCommands, $nickName);
                                $payId = random_string('alnum', 16);

                                if($rconResponse['status'] == true) {
                                    $_SESSION['purchaseResponse'] = array(
                                        'status'   =>  true,
                                        'service'  =>  $serviceName,
                                        'server'   =>  $serverName,
                                        'payid'    =>  $payId,
                                        'method'   =>  'sms'
                                    );
                                } else {
                                    $_SESSION['purchaseResponse'] = array(
                                        'status'   =>  false,
                                        'service'  =>  $serviceName,
                                        'server'   =>  $serverName,
                                        'payid'    =>  $payId,
                                        'method'   =>  'sms'
                                    );
                                }
                            } else {
                                $_SESSION['messageDangerSmall'] = $smsResponse['message'];
                                redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                            }
                        } else {
                            $_SESSION['messageDangerSmall'] = "Operator płatności SMS Premium jest błędnie skonfigurowany!";
                            redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                        }

                        $data['buyerName'] = $nickName;
                        $data['service'] = $service['id'];
                        $data['server'] = $server['id'];
                        $data['method'] = "SMS Premium";
                        $data['details'] = "Kod z SMS: " . $smsCode;
                        $data['payId'] = $payId;
                        if($rconResponse['status'] == true) {
                            $data['status'] = "success";
                            $data['profit'] = getNettoPrice($service['smsConfig']['smsNumber'], 1);
                        } else {
                            $data['status'] = "failed";
                            $data['profit'] = "0";
                        }
                        $data['date'] = time();

                        $this->PurchasesM->add($data);
                        unset($data);

                        if($rconResponse['status'] == true && $settings['shopDiscordWebhookEnabled'] == 1) {
                            $hookContent = json_encode([
                                "username" => $settings['shopDiscordWebhookBotName'],
                                "avatar_url" => "https://cdn-n.divshop.pro/images/divshop-avatar.png",
                                "tts" => false,
                                "embeds" => [
                                    [
                                        "title" => $settings['shopDiscordWebhookEmbedTitle'],
                                        "type" => "rich",
                                        "description" => str_replace(array('{buyer}', '{BUYER}'), $nickName, str_replace(array('{service}', '{SERVICE}'), $serviceName, $settings['shopDiscordWebhookDesc'])),
                                        "timestamp" => date("c"),
                                        "color" => hexdec($settings['shopDiscordWebhookHex']),
                                        "footer" => [
                                            "text" => "DIVShop.pro",
                                        ],
                                    ]
                                ],
                                "content" => "",
                            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                            $curl = curl_init();
                            curl_setopt_array($curl, [
                                CURLOPT_URL => $settings['shopDiscordWebhookUrl'],
                                CURLOPT_POST => true,
                                CURLOPT_POSTFIELDS => $hookContent,
                                CURLOPT_HTTPHEADER => [
                                    "Content-Type: application/json"
                                ]
                            ]);
                            curl_exec($curl);
                            curl_close($curl);
                        }

                        redirect($this->config->base_url('payments/ended'));

                    } else {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Probably the RCON password is incorrect");
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd podczas łączenia z serwerem. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                } else {
                    $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
                    redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                }
            } else {
                $this->load->view('errors/html/error_404.php');
                return;
            }
        }
    }

    public function paypal() {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!empty($_POST)) {

                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
                $this->load->model('ServersM');
                $this->load->model('ServicesM');
                $this->load->model('PaymentsM');
                $this->load->model('PaypalPayM');
                $this->load->model('PurchasesM');
                $this->load->helper('rcon_helper');
                $settings = $this->SettingsM->getSettings();
                
                $this->form_validation->set_rules('nickName', 'nickName', 'required|trim');
                $this->form_validation->set_rules('serviceId', 'serviceId', 'required|trim');
                $this->form_validation->set_rules('serviceName', 'serviceName', 'required|trim');
                $this->form_validation->set_rules('serverId', 'serverId', 'required|trim');
                $this->form_validation->set_rules('serverName', 'serverId', 'required|trim');
                
                $nickName = $this->input->post('nickName');
                $serviceId = $this->input->post('serviceId');
                $serviceName = $this->input->post('serviceName');
                $serverId = $this->input->post('serverId');
                $serverName = $this->input->post('serverName');

                if($this->form_validation->run() === TRUE) {

                    if($this->input->post('acceptRules') == null) {
                        $_SESSION['messageDangerSmall'] = "Zaakceptowanie regulaminu płatności jest wymagane!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    if(!$service = $this->ServicesM->getBy('id', $serviceId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                    if(!$server = $this->ServersM->getBy('id', $serverId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    try {
                        $query = new MinecraftPing($server['ip'], $server['port']);
                        $servers['status']['online'] = true;
                    } catch(MinecraftPingException $e) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
                        $_SESSION['messageDangerSmall'] = "Serwer, na którym próbujesz zakupić usługę jest aktualnie wyłączony";
                        redirect($this->config->base_url('shop'));
                    } finally {
                        if (isset($query)) $query->Close();
                    }

                    $paypal = $this->PaymentsM->get(2);
                    $payments = array(
                        'paypal'    =>  json_decode($paypal['config'], true)
                    );

                    $serverData = array(
                        'ipAddress'     =>  $server['ip'],
                        'rconPort'      =>  $server['rconPort'],
                        'rconPassword'  =>  $server['rconPass']
                    );

                    $rconConnecion = checkRconConnection($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword']);

                    if($rconConnecion['status'] == true) {
                        $bodyD['paypalData'] = array(
                            'business'       =>  $payments['paypal']['address'],
                            'item_name'      =>  'Usługa ' . $service['name'] . ' (ID: #' . $service['id'] . ') | Serwer ' . $serverName . ' (ID: #' . $serviceId . ')',
                            'item_number'    =>  $service['id'],
                            'amount'         =>  $service['paypalCost'],
                            'custom'         =>  random_string('alnum', 64),
                            'quantity'       =>  1,
                            'currency_code'  =>  "PLN"
                        );

                        $data['service'] = $bodyD['paypalData']['item_number'];
                        $data['buyer'] = $nickName;
                        $data['hash'] = $bodyD['paypalData']['custom'];
                        $data['gross'] = $bodyD['paypalData']['amount'];
                        $data['currency'] = $bodyD['paypalData']['currency_code'];
                        $data['status'] = "CREATED";

                        if(!$this->PaypalPayM->add($data)) {
                            $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                            redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                        }

                        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                        $this->load->model('NewsM');
                        $this->load->model('ModulesM');
                        $this->load->model('PagesM');
                        $api = new DIVShopAPI();
                        $bodyD['divsAPI'] = array(
                            'divsVersion'  =>  $api->get_current_version()
                        );
                        $bodyD['modules'] = $this->ModulesM->getAll();
                        $bodyD['news'] = $this->NewsM->getAll();
                        $bodyD['pages'] = $this->PagesM->getAll();

                        /**  Head section  */
                        $headerD['settings'] = $this->SettingsM->getSettings();
                        $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                        $headerD['pageSubtitle'] = 'Tworzenie płatności PayPal';
                        $this->load->view('components/Header', $headerD);

                        /**  Body section */
                        $_SESSION['paymentInfo'] = array(
                            'hash'   => $bodyD['paypalData']['custom'],
                            'status' => 'created'
                        );
                        $bodyD['pageCode'] = "createPayment";
                        $this->load->view('payments/Paypal', $bodyD);
                    
                    } else {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Probably the RCON password is incorrect");
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd podczas łączenia z serwerem. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                } else {
                    $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
                    redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                }
            } else {
                $this->load->view('errors/html/error_404.php');
                return;
            }
        }
    }

    public function paypalCancel() {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!isset($_SESSION['paymentInfo'])) {
                $this->load->view('errors/html/error_404.php');
                return;
            } else {

                $this->load->model('PaypalPayM');
                $data['status'] = "CANCELLED";
                if($this->PaypalPayM->update($_SESSION['paymentInfo']['hash'], $data)) {

                    require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                    $this->load->model('NewsM');
                    $this->load->model('ModulesM');
                    $this->load->model('PagesM');
                    $api = new DIVShopAPI();
                    $bodyD['divsAPI'] = array(
                        'divsVersion'  =>  $api->get_current_version()
                    );
                    $bodyD['modules'] = $this->ModulesM->getAll();
                    $bodyD['news'] = $this->NewsM->getAll();
                    $bodyD['pages'] = $this->PagesM->getAll();

                    /**  Head section  */
                    $headerD['settings'] = $this->SettingsM->getSettings();
                    $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                    $headerD['pageSubtitle'] = 'Płatność anulowana';
                    $this->load->view('components/Header', $headerD);

                    /**  Body section */
                    $bodyD['pageCode'] = "cancelledPayment";
                    $this->load->view('payments/Paypal', $bodyD);

                } else {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }
            }
        }
    }

    public function paypalSuccess() {
        
        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!isset($_SESSION['paymentInfo'])) {
                $this->load->view('errors/html/error_404.php');
                return;
            } else {

                if(!count($_GET)) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }

                $this->load->model('PaypalPayM');
                $this->load->model('ServicesM');
                $this->load->model('ServersM');
                $this->load->model('PurchasesM');
                $this->load->helper('rcon_helper');

                $paymentData = array(
                    'amount' => $_GET['amt'],
                    'currency' => $_GET['cc'],
                    'hash' => $_GET['cm'],
                    'service' => $_GET['item_number'],
                    'transactionId' => $_GET['tx'],
                    'status' => $_GET['st']
                );

                if($payment = $this->PaypalPayM->getBy('hash', $paymentData['hash'])) {
                    if($service = $this->ServicesM->getBy('id', $paymentData['service'])) {

                        $server = $this->ServersM->getBy('id', $service['server']);

                        if($payment['status'] == "CREATED") {
                            require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                            $this->load->model('NewsM');
                            $this->load->model('ModulesM');
                            $this->load->model('PagesM');
                            $api = new DIVShopAPI();
                            $bodyD['divsAPI'] = array(
                                'divsVersion'  =>  $api->get_current_version()
                            );
                            $bodyD['modules'] = $this->ModulesM->getAll();
                            $bodyD['news'] = $this->NewsM->getAll();
                            $bodyD['pages'] = $this->PagesM->getAll();

                            /**  Head section  */
                            $headerD['settings'] = $this->SettingsM->getSettings();
                            $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                            $headerD['pageSubtitle'] = 'Weryfikacja płatności PayPal';
                            $this->load->view('components/Header', $headerD);

                            /**  Body section  */
                            $bodyD['pageCode'] = "waitingForPaymentVerify";
                            $this->load->view('payments/Paypal', $bodyD);
                            return;
                        }

                        if($paymentData['amount'] != number_format($payment['gross'], 2, '.', '') || $paymentData['currency'] != $payment['currency'] || $paymentData['service'] != $payment['service'] || $paymentData['transactionId'] != $payment['transactionId'] && (!isset($_SESSION['paymentInfo']))) {
                            $this->load->view('errors/html/error_404.php');
                            return;
                        }

                        $serverData = array(
                            'ipAddress'     =>  $server['ip'],
                            'rconPort'      =>  $server['rconPort'],
                            'rconPassword'  =>  $server['rconPass']
                        );


                        $serverCommands = explode('; ', $service['commands']);
                        $rconResponse = sendRconCommand($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword'], $serverCommands, $payment['buyer']);
                        
                        $payId = random_string('alnum', 16);

                        if($rconResponse['status'] == true) {
                            $data['status'] = "ENDED";
                        } else {
                            $data['status'] = "RCON_ERROR";
                        }
                        $this->PaypalPayM->update($payment['hash'], $data);
                        unset($data);

                        $data['buyerName'] = $payment['buyer'];
                        $data['service'] = $service['id'];
                        $data['server'] = $service['server'];
                        $data['method'] = "PayPal";
                        $data['details'] = "E-mail osoby płacącej: " . $payment['payerEmail'];
                        $data['payId'] = $payId;
                        if($rconResponse['status'] == true) {
                            $data['status'] = "success";
                            $data['profit'] = $payment['gross'] - $payment['fee'];
                        } else {
                            $data['status'] = "failed";
                            $data['profit'] = "0";
                        }
                        $data['date'] = time();

                        $this->PurchasesM->add($data);
                        unset($data);

                        if(($paymentData['status'] == "Completed" && $rconResponse['status'] == true) && $settings['shopDiscordWebhookEnabled'] == 1) {
                            $hookContent = json_encode([
                                "username" => $settings['shopDiscordWebhookBotName'],
                                "avatar_url" => "https://cdn-n.divshop.pro/images/divshop-avatar.png",
                                "tts" => false,
                                "embeds" => [
                                    [
                                        "title" => $settings['shopDiscordWebhookEmbedTitle'],
                                        "type" => "rich",
                                        "description" => str_replace(array('{buyer}', '{BUYER}'), $payment['buyer'], str_replace(array('{service}', '{SERVICE}'), $service['name'], $settings['shopDiscordWebhookDesc'])),
                                        "timestamp" => date("c"),
                                        "color" => hexdec($settings['shopDiscordWebhookHex']),
                                        "footer" => [
                                            "text" => "DIVShop.pro",
                                        ],
                                    ]
                                ],
                                "content" => "",
                            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                            $curl = curl_init();
                            curl_setopt_array($curl, [
                                CURLOPT_URL => $settings['shopDiscordWebhookUrl'],
                                CURLOPT_POST => true,
                                CURLOPT_POSTFIELDS => $hookContent,
                                CURLOPT_HTTPHEADER => [
                                    "Content-Type: application/json"
                                ]
                            ]);
                            curl_exec($curl);
                            curl_close($curl);
                        }

                        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                        $this->load->model('NewsM');
                        $this->load->model('ModulesM');
                        $this->load->model('PagesM');
                        $api = new DIVShopAPI();
                        $bodyD['divsAPI'] = array(
                            'divsVersion'  =>  $api->get_current_version()
                        );
                        $bodyD['modules'] = $this->ModulesM->getAll();
                        $bodyD['news'] = $this->NewsM->getAll();
                        $bodyD['pages'] = $this->PagesM->getAll();

                        /**  Head section  */
                        $headerD['settings'] = $this->SettingsM->getSettings();
                        $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                        if($rconResponse['status'] == true) {
                            $headerD['pageSubtitle'] = 'Płatność PayPal zakończona';
                        } else {
                            $headerD['pageSubtitle'] = 'Płatność nieudana';
                        }
                        $this->load->view('components/Header', $headerD);

                        /**  Body section  */
                        if($rconResponse['status'] == true) {
                            $bodyD['pageCode'] = "paymentSuccessful";
                        } else {
                            $bodyD['pageCode'] = "paymentFailed";
                        }
                        $bodyD['paymentInfo'] = array(
                            'payid' => $payId,
                            'service' => $service['name'],
                            'server' => $server['name']
                        );
                        $this->load->view('payments/Paypal', $bodyD);
                        return;

                    } else {
                        $this->load->view('errors/html/error_404.php');
                        return;
                    }

                } else {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }
            }
        }
    }

    public function transfer() {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!empty($_POST)) {

                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
                require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
                $this->load->model('ServicesM');
                $this->load->model('ServersM');
                $this->load->model('PaymentsM');
                $this->load->model('PurchasesM');
                $this->load->model('TransfersPayM');
                $this->load->helper('rcon_helper');
                $settings = $this->SettingsM->getSettings();

                $this->form_validation->set_rules('nickName', 'nickName', 'required|trim');
                $this->form_validation->set_rules('serviceId', 'serviceId', 'required|trim');
                $this->form_validation->set_rules('serviceName', 'serviceName', 'required|trim');
                $this->form_validation->set_rules('serverId', 'serverId', 'required|trim');
                $this->form_validation->set_rules('serverName', 'serverId', 'required|trim');

                $nickName = $this->input->post('nickName');
                $serviceId = $this->input->post('serviceId');
                $serviceName = $this->input->post('serviceName');
                $serverId = $this->input->post('serverId');
                $serverName = $this->input->post('serverName');

                if($this->form_validation->run() === TRUE) {

                    if($this->input->post('acceptRules') == null) {
                        $_SESSION['messageDangerSmall'] = "Zaakceptowanie regulaminu płatności jest wymagane!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    if(!$service = $this->ServicesM->getBy('id', $serviceId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                    if(!$server = $this->ServersM->getBy('id', $serverId)) {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }

                    try {
                        $query = new MinecraftPing($server['ip'], $server['port']);
                        $servers['status']['online'] = true;
                    } catch(MinecraftPingException $e) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
                        $_SESSION['messageDangerSmall'] = "Serwer, na którym próbujesz zakupić usługę jest aktualnie wyłączony";
                        redirect($this->config->base_url('shop'));
                    } finally {
                        if (isset($query)) $query->Close();
                    }

                    $transferSettings = $this->PaymentsM->get(3);
                    $payments = array(
                        'transfer' => json_decode($transferSettings['config'], true)
                    );
                    $payments = array(
                        'transfer' => $payments['transfer']['transfer']
                    );

                    $serverData = array(
                        'ipAddress' => $server['ip'],
                        'rconPort' => $server['rconPort'],
                        'rconPassword' => $server['rconPass']
                    );
                    $rconConnecion = checkRconConnection($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword']);

                    if($rconConnecion['status'] == true) {

                        $bodyD['transferData']['shopid'] = $payments['transfer']['shopid'];
                        $bodyD['transferData']['signature'] = hash('sha256', $payments['transfer']['shopid'] . $payments['transfer']['hash'] . number_format($service['transferCost'], 2, '.', ''));
                        $bodyD['transferData']['amount'] = number_format($service['transferCost'], 2, '.', '');
                        $bodyD['transferData']['control'] = random_string('alnum', 64);
                        $bodyD['transferData']['return_urlc'] = $this->config->base_url('payments/transfer/ipn');
                        $bodyD['transferData']['return_url'] = $this->config->base_url('payments/transfer/end/' . $bodyD['transferData']['control']);
                        $bodyD['transferData']['description'] = "Usługa " . $service['name'] . " (Serwer " . $server['name'] . ") dla użytkownika " . $nickName;

                        $data['buyer'] = $nickName;
                        $data['service'] = $service['id'];
                        $data['serviceName'] = $service['name'];
                        $data['serverName'] = $server['name'];
                        $data['payId'] = random_string('alnum', 16);
                        $data['control'] = $bodyD['transferData']['control'];
                        $data['amount'] = $bodyD['transferData']['amount'];
                        $data['status'] = "CREATED";

                        if($this->TransfersPayM->add($data)) {

                            redirect("https://microsms.pl/api/bankTransfer/?shopid=" . $bodyD['transferData']['shopid'] . "&signature=" . $bodyD['transferData']['signature'] . "&amount=" . $bodyD['transferData']['amount'] . "&control=" . $bodyD['transferData']['control'] . "&return_urlc=" . $bodyD['transferData']['return_urlc'] . "&return_url=" . $bodyD['transferData']['return_url'] . "&description=" . $bodyD['transferData']['description']);

                        } else {
                            $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                            redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                        }
                    } else {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Probably the RCON password is incorrect");
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd podczas łączenia z serwerem. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                    }
                } else {
                    $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
                    redirect($this->config->base_url('shop/' . $serverId . '-' . getShopUrl($serverName) . '/' . 'service/' . $serviceId . '-' . getServiceUrl($serviceName)));
                }
            } else {
                $this->load->view('errors/html/error_404.php');
                return;
            }
        }
    }

    public function transferEnd($hash = null) {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            $this->load->model('ServicesM');
            $this->load->model('ServersM');
            $this->load->model('TransfersPayM');
            $this->load->model('PaymentsM');

            if($hash != null) {

                if(!$transferData = $this->TransfersPayM->getBy('control', $hash)) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                }

                if($transferData['status'] == "CREATED") {
                    require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                    $this->load->model('NewsM');
                    $this->load->model('ModulesM');
                    $this->load->model('PagesM');
                    $api = new DIVShopAPI();
                    $bodyD['divsAPI'] = array(
                        'divsVersion'  =>  $api->get_current_version()
                    );
                    $bodyD['modules'] = $this->ModulesM->getAll();
                    $bodyD['news'] = $this->NewsM->getAll();
                    $bodyD['pages'] = $this->PagesM->getAll();

                    /**  Head section  */
                    $headerD['settings'] = $this->SettingsM->getSettings();
                    $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                    $headerD['pageSubtitle'] = 'Weryfikacja Przelewu';
                    $this->load->view('components/Header', $headerD);

                    /**  Body section  */
                    $bodyD['pageCode'] = "waitingForPaymentVerify";
                    $this->load->view('payments/Paypal', $bodyD);
                    return;
                }

                require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                $this->load->model('NewsM');
                $this->load->model('ModulesM');
                $this->load->model('PagesM');
                $api = new DIVShopAPI();
                $bodyD['divsAPI'] = array(
                    'divsVersion'  =>  $api->get_current_version()
                );
                $bodyD['modules'] = $this->ModulesM->getAll();
                $bodyD['news'] = $this->NewsM->getAll();
                $bodyD['pages'] = $this->PagesM->getAll();

                
                $headerD['settings'] = $this->SettingsM->getSettings();
                $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                if($transferData['status'] == "ENDED_RCON_ERROR") {
                    $headerD['pageSubtitle'] = 'Płatność nieudana';
                }
                if($transferData['status'] == "ENDED_SUCCESSFULY") {
                    $headerD['pageSubtitle'] = 'Płatność przelewem zakończona';
                }

                $this->load->view('components/Header', $headerD);

                if($transferData['status'] == "ENDED_RCON_ERROR") {
                    $bodyD['pageCode'] = "endedRconError";
                }
                if($transferData['status'] == "ENDED_SUCCESSFULY") {
                    $bodyD['pageCode'] = "paymentSuccessful";
                }
                $bodyD['paymentInfo'] = array(
                    'payid' => $transferData['payId'],
                    'service' => $transferData['serviceName'],
                    'server' => $transferData['serverName']
                );

                $this->load->view('payments/Transfer', $bodyD);
                return;

            } else {
                show_error('Błędny numer kontroli transakcji');
                return;
            }
        }
    }

    public function transferIPN() {

        if(isset($_POST['status'])) {

            $this->load->model('PaymentsM');
            $this->load->model('PurchasesM');
            $this->load->model('TransfersPayM');
            $this->load->model('ServicesM');
            $this->load->model('ServersM');
            $this->load->helper('rcon_helper');
            $settings = $this->SettingsM->getSettings();

            $ipnStatus = $_POST['status'];
            $ipnUserId = $_POST['userid'];
            $ipnEmail = $_POST['email'];
            $ipnOrderId = $_POST['orderID'];
            $ipnAmountUni = $_POST['amountUni'];
            $ipnAmountPay = $_POST['amountPay'];
            $ipnControl = $_POST['control'];
            $ipnTest = $_POST['test'];

            if($transferData = $this->TransfersPayM->getBy('control', $ipnControl)) {

                $transferSettings = $this->PaymentsM->get(3);
                $payments = array(
                    'transfer' => json_decode($transferSettings['config'], true)
                );
                $payments = array(
                    'transfer' => $payments['transfer']['transfer']
                );

                if($payments['transfer']['userid'] == $ipnUserId || number_format($transferData['amount'], 2, '.', '') == $ipnAmountUni) {

                    $data['orderId'] = $ipnOrderId;
                    $data['payerEmail'] = urldecode($ipnEmail);
                    $data['profit'] = $ipnAmountPay;
                    $data['test'] = ($ipnTest == true) ? 1 : 0;

                    if($ipnStatus == false) {
                        $data['status'] = "CANCELED";
                    }
                    $data['status'] = "SUCCESS";
                    if(!$this->TransfersPayM->update($transferData['control'], $data)) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Transfer information cannot be updated");
                    }

                    if(!$service = $this->ServicesM->getBy('id', $transferData['service'])) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Service information cannot be obtained");
                    }
                    if(!$server = $this->ServersM->getBy('id', $service['server'])) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Server information cannot be obtained");
                    }

                    $serverData = array(
                        'ipAddress'     =>  $server['ip'],
                        'rconPort'      =>  $server['rconPort'],
                        'rconPassword'  =>  $server['rconPass']
                    );

                    $serverCommands = explode('; ', $service['commands']);
                    $rconResponse = sendRconCommand($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword'], $serverCommands, $transferData['buyer']);

                    if($rconResponse['status'] == true) {
                        $data['status'] = "ENDED_SUCCESSFULY";
                    } else {
                        $data['status'] = "ENDED_RCON_ERROR";
                    }
                    if(!$this->TransfersPayM->update($transferData['control'], $data)) {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Transfer information cannot be updated");
                    }

                    $purchase['buyerName'] = $transferData['buyer'];
                    $purchase['service'] = $service['id'];
                    $purchase['server'] = $server['id'];
                    $purchase['method'] = "Przelew";
                    $purchase['details'] = "E-mail kupującego: " . $transferData['payerEmail'] . "<br>" . ($transferData['test'] == 1) ? "Przelew testowy": "";
                    $purchase['payId'] = $transferData['payId'];
                    if($rconResponse['status'] == true) {
                        $purchase['status'] = "success";
                        $purchase['profit'] = $transferData['profit'];
                    } else {
                        $purchase['status'] = "failed";
                        $purchase['profit'] = "0";
                    }
                    $purchase['date'] = time();

                    $this->PurchasesM->add($purchase);
                    unset($purchase);

                    if($rconResponse['status'] == true && $settings['shopDiscordWebhookEnabled'] == 1) {
                        $hookContent = json_encode([
                            "username" => $settings['shopDiscordWebhookBotName'],
                            "avatar_url" => "https://cdn-n.divshop.pro/images/divshop-avatar.png",
                            "tts" => false,
                            "embeds" => [
                                [
                                    "title" => $settings['shopDiscordWebhookEmbedTitle'],
                                    "type" => "rich",
                                    "description" => str_replace(array('{buyer}', '{BUYER}'), $transferData['buyer'], str_replace(array('{service}', '{SERVICE}'), $service['name'], $settings['shopDiscordWebhookDesc'])),
                                    "timestamp" => date("c"),
                                    "color" => hexdec($settings['shopDiscordWebhookHex']),
                                    "footer" => [
                                        "text" => "DIVShop.pro",
                                    ],
                                ]
                            ],
                            "content" => "",
                        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => $settings['shopDiscordWebhookUrl'],
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $hookContent,
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: application/json"
                            ]
                        ]);
                        curl_exec($curl);
                        curl_close($curl);
                    }
                } else {
                    log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Transfer User ID or amount doesn't match with MicroSMS packets");
                }
            } else {
                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] The hash doesn't match");
            }
            exit('OK');
        } else {
            $this->load->view('errors/html/error_404.php');
            return;
        }
    }

    public function paypalIPN() {

        if(!empty($_POST)) {
        
            require_once(APPPATH . 'libraries/payments/PaypalIPN.php');
            $this->load->model('PaypalPayM');
            $this->load->model('PaymentsM');
            $ipn = new PaypalIPN();

            if($this->config->item('paypal_sandbox') == TRUE) {
                $ipn->useSandbox();
            }
            $verified = $ipn->verifyIPN();

            if($verified) {
                foreach($_POST as $key => $value) {
                    if($key == "business") {
                        $payData['business'] = $value;
                    }
                    if($key == "receiver_email") {
                        $payData['receiver_email'] = $value;
                    }
                    if($key == "item_number") {
                        $payData['service'] = $value;
                    }
                    if($key == "txn_id") {
                        $payData['txn_id'] = $value;
                    }
                    if($key == "custom") {
                        $payData['custom'] = $value;
                    }
                    if($key == "mc_gross") {
                        $payData['mc_gross'] = $value;
                    }
                    if($key == "mc_currency") {
                        $payData['mc_currency'] = $value;
                    }
                    if($key == "mc_fee") {
                        $payData['mc_fee'] = $value;
                    }
                    if($key == "payer_email") {
                        $payData['payer_email'] = $value;
                    }
                    if($key == "payment_status") {
                        $payData['payment_status'] = $value;
                    }
                }

                $paypal = $this->PaymentsM->get(2);
                $payments = array(
                    'paypal'    =>  json_decode($paypal['config'], true)
                );

                if($payment = $this->PaypalPayM->getBy('hash', $payData['custom'])) {
                    if($payData['service'] == $payment['service'] && $payData['mc_gross'] == $payment['gross'] && $payData['mc_currency'] == $payment['currency'] && ($payData['receiver_email'] || $payData['business']) == $payments['paypal']['address']) {
                        $data['transactionId'] = $payData['txn_id'];
                        $data['status'] = strtoupper($payData['payment_status']);
                        $data['fee'] = $payData['mc_fee'];
                        $data['payerEmail'] = strtolower($payData['payer_email']);
                        $this->PaypalPayM->update($payData['custom'], $data);
                    } else {
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Error 1");
                    }
                } else {
                    log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] The hash doesn't match");
                }
            }
            header("HTTP/1.1 200 OK");
        } else {
            $this->load->view('errors/html/error_404.php');
            return;
        }
    }

    public function ended() {
        
        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {
			
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            if(!isset($_SESSION['purchaseResponse'])) {
                $this->load->view('errors/html/error_404');
                return;
            } else {

                require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                $this->load->model('NewsM');
                $this->load->model('ModulesM');
                $api = new DIVShopAPI();
                $bodyD['divsAPI'] = array(
                    'divsVersion'  =>  $api->get_current_version()
                );
                $bodyD['modules'] = $this->ModulesM->getAll();
                $bodyD['news'] = $this->NewsM->getAll();

                /**  Head section  */
                $headerD['settings'] = $this->SettingsM->getSettings();
                $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                $headerD['pageSubtitle'] = 'Płatność zakończona';
                $this->load->view('components/Header', $headerD);

                /**  Body section */
                $this->load->view('payments/Ended', $bodyD);
            }
        }
    }

    public function voucher() {

        if(!empty($_POST)) {

            require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPing.php');
            require_once(APPPATH . 'libraries/minecraft-lib/MinecraftPingException.php');
            $this->load->model('VouchersM');
            $this->load->model('ServicesM');
            $this->load->model('ServersM');
            $this->load->model('PurchasesM');
            $this->load->helper('rcon_helper');
            $settings = $this->SettingsM->getSettings();
            
            $this->form_validation->set_rules('nickName', 'nickName', 'required|trim');
            $this->form_validation->set_rules('voucherCode', 'voucherCode', 'required|trim|max_length['. (strlen($settings['voucherPrfx']) + $settings['voucherLength']) . ']');

            if($this->form_validation->run() === TRUE) {

                $nickName = $this->input->post('nickName');
                $voucherCode = $this->input->post('voucherCode');

                if($voucher = $this->VouchersM->getBy('code', $voucherCode)) {
                    if($service = $this->ServicesM->getBy('id', $voucher['service'])) {
                        if($server = $this->ServersM->getBy('id', $service['server'])) {
                            
                            try {
                                $query = new MinecraftPing($server['ip'], $server['port']);
                                $servers['status']['online'] = true;
                            } catch(MinecraftPingException $e) {
                                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] MinecraftPingException: " . $e->getMessage());
                                $_SESSION['messageDangerSmall'] = "Serwer, na którym próbujesz zrealizować voucher jest aktualnie wyłączony";
                                redirect($this->config->base_url('voucher'));
                            } finally {
                                if (isset($query)) $query->Close();
                            }

                            $serverData = array(
                                'ipAddress'     =>  $server['ip'],
                                'rconPort'      =>  $server['rconPort'],
                                'rconPassword'  =>  $server['rconPass']
                            );

                            $rconConnecion = checkRconConnection($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword']);
                            if($rconConnecion['status'] == true) {

                                $serverCommands = explode('; ', $service['commands']);
                                $rconResponse = sendRconCommand($serverData['ipAddress'], $serverData['rconPort'], $serverData['rconPassword'], $serverCommands, $nickName);

                                $data['buyerName'] = $nickName;
                                $data['service'] = $service['id'];
                                $data['server'] = $service['server'];
                                $data['method'] = "Voucher";
                                $data['details'] = "Kod: " . $voucherCode;
                                $data['payId'] = random_string('alnum', 16);
                                if($rconResponse['status'] == true) {
                                    $data['status'] = "success";
                                } else {
                                    $data['status'] = "failed";
                                }
                                $data['profit'] = 0;
                                $data['date'] = time();

                                $this->PurchasesM->add($data);

                                if(!$this->VouchersM->delete($voucher['id'])) {
                                    $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                                    redirect($this->config->base_url('voucher'));
                                }

                                if($rconResponse['status'] == true) {
                                    $_SESSION['messageSuccessSmall'] = "Voucher został wykorzystany! Usługa " . $service['name'] . " na serwerze " . $server['name'] . " została aktywowana!";
                                } else {
                                    $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd podczas realizacji Vouchera. Zachowaj kod i zgłoś się do Administratora!";
                                }
                                redirect($this->config->base_url('voucher'));
                            
                            } else {
                                log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 1) . "] Probably the RCON password is incorrect");
                                $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd podczas łączenia z serwerem. Spróbuj jeszcze raz!";
                                redirect($this->config->base_url('voucher'));
                            }
                        } else {
                            $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                            redirect($this->config->base_url('voucher'));
                        }
                    } else {
                        $_SESSION['messageDangerSmall'] = "Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz!";
                        redirect($this->config->base_url('voucher'));
                    }
                } else {
                    $_SESSION['messageDangerSmall'] = "Podany kod vouchera jest niepoprawny lub został już wykorzystany!";
                    redirect($this->config->base_url('voucher'));
                }
            } else {
                $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
                redirect($this->config->base_url('voucher'));
            }
        } else {
            $this->load->view('errors/html/error_404.php');
            return;
        }
    }
}