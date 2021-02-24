<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Antybot extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {

		$settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {

			/**  Head section  */
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			/**  Body section  */
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = $bodyD['pageBreak']['pageBreakDescription'];
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            $this->load->model('ModulesM');
            $modules = $this->ModulesM->getAll();
            
            if($modules[3]['moduleName'] == "antibot" && $modules[3]['moduleEnabled'] == 1) {
		
                require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                $this->load->model('ModulesM');
                $this->load->model('NewsM');
                $this->load->model('AntybotM');
                $this->load->model('PagesM');
                $api = new DIVShopAPI();
                $bodyD['divsAPI'] = array(
                    'divsVersion'  =>  $api->get_current_version()
                );

                /**  Head section  */
                $headerD['settings'] = $this->SettingsM->getSettings();
                $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                $headerD['pageSubtitle'] = "Weryfikacja Antybot";
                $this->load->view('components/Header', $headerD);

                /**  Body section  */
                $bodyD['modules'] = $this->ModulesM->getAll();
                $bodyD['news'] = $this->NewsM->getAll();
                $bodyD['pages'] = $this->PagesM->getAll();

                $this->load->view('Antybot', $bodyD);

            } else {
                $this->load->view('errors/html/error_404.php');
                    return;
            }
		}
    }
    
    public function verifyUser() {

        $this->load->model('ModulesM');
        $modules = $this->ModulesM->getAll();
        
        if($modules[3]['moduleName'] == "antibot" && $modules[3]['moduleEnabled'] == 1) {

            $settings = $this->SettingsM->getSettings();

            $this->form_validation->set_rules('playerUsername', 'playerUsername', 'required|trim|max_length[56]');

            if($this->form_validation->run() === TRUE) {
                $playerUsername = $this->input->post('playerUsername');
                $recaptchaPost = $this->input->post('g-recaptcha-response');

                $this->load->model('AntybotM');

                $recaptchaData = array(
                    'secret'    =>  $settings['recaptchaSecretKey'],
                    'response'  =>  $recaptchaPost
                );

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($recaptchaData),
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_RETURNTRANSFER => true
                ]);
                $recaptchaResponse = curl_exec($curl);
                curl_close($curl);
                $recaptchaResponse = json_decode($recaptchaResponse, true);

                if($recaptchaResponse['success'] == true) {
                    if(ctype_alnum($playerUsername)) {
                        if($playerUsername = $this->AntybotM->getBy('username', $playerUsername)) {
                            if($playerUsername['status'] == 1) {
                                $_SESSION['messageSuccessSmall'] = "Ten nick został już wcześniej zweryfikowany :)";
                                redirect($this->config->base_url('antybot'));
                            } else {
                                $data['status'] = 1;
                                if(!$this->AntybotM->update($playerUsername['username'], $data)) {
                                    $_SESSION['messageDangerSmall'] = "Wystąpił błąd podczas łączenia z bazą danych!";
                                    redirect($this->config->base_url('antybot'));
                                }
                            }
                        } else {
                            $_SESSION['messageDangerSmall'] = "Ten nick nigdy nie próbował wejść na serwer.";
                            redirect($this->config->base_url('antybot'));
                        }
                    } else {
                        $_SESSION['messageDangerSmall'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków, znaków specjalnych itp.)";
                        redirect($this->config->base_url('antybot'));
                    }
                } else {
                    $_SESSION['messageDangerSmall'] = "Musisz potwierdzić, że nie jesteś robotem - rozwiąż reCaptchę";
                    redirect($this->config->base_url('antybot'));
                }

                $_SESSION['messageSuccessSmall'] = "Pomyślnie zweryfikowano!";
                sleep(1);
                redirect($this->config->base_url('antybot'));

            } else {
                $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
                redirect($this->config->base_url('antybot'));
            }
        } else {
            $this->load->view('errors/html/error_404.php');
            return;
        }
    }
}
