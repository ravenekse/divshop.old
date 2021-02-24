<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index() {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
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
        $bodyD['modules'] = $this->ModulesM->getAll();
        $this->load->view('panel/Settings', $bodyD);
    }

    public function saveSettings() {

        $this->form_validation->set_rules('settingsPageTitle', 'settingsPageTitle', 'required|trim');
        $this->form_validation->set_rules('settingsPageDescription', 'settingsPageDescription', 'trim');
        $this->form_validation->set_rules('settingsPageTags', 'settingsPageTags', 'trim');
        $this->form_validation->set_rules('settingsPageCharset', 'settingsPageCharset', 'required|trim');
        $this->form_validation->set_rules('settingsPagePreloader', 'settingsPagePreloader', 'trim');
        $this->form_validation->set_rules('settingsPageEnabled', 'settingsPageEnabled', 'trim');
        $this->form_validation->set_rules('settingsPageBreakDescription', 'settingsPageBreakDescription', 'required|trim');
        $this->form_validation->set_rules('settingsVoucherPrfx', 'settingsVoucherPrfx', 'required|trim');
        $this->form_validation->set_rules('settingsVoucherLength', 'settingsVoucherLength', 'required|trim');
        $this->form_validation->set_rules('settingsPageBackground', 'settingsPageBackground', 'trim');
        $this->form_validation->set_rules('settingsPageCustomCSS', 'settingsPageCustomCSS', 'trim');
        $this->form_validation->set_rules('settingsPageTheme', 'settingsPageTheme', 'required|trim');
        $this->form_validation->set_rules('settingsSidebarPosition', 'settingsSidebarPosition', 'trim');
        $this->form_validation->set_rules('settingsWebhookUrl', 'settingsWebhookUrl', 'trim');
        $this->form_validation->set_rules('settingsWebhookBotName', 'settingsWebhookBotName', 'required|trim');
        $this->form_validation->set_rules('settingsWebhookEmbedTitle', 'settingsWebhookEmbedTitle', 'required|trim');
        $this->form_validation->set_rules('settingsWebhookEmbedContent', 'settingsWebhookEmbedContent', 'required|trim');
        $this->form_validation->set_rules('settingsWebhookEmbedColor', 'settingsWebhookEmbedColor', 'required|trim');
        $this->form_validation->set_rules('settingsWebhookEnabled', 'settingsWebhookEnabled', 'trim');
        if($this->input->post('settingsRecaptchaSiteKey') != null || $this->input->post('settingsRecaptchaSecretKey') != null) {
            $this->form_validation->set_rules('settingsRecaptchaSiteKey', 'settingsRecaptchaSiteKey', 'required|trim');
            $this->form_validation->set_rules('settingsRecaptchaSecretKey', 'settingsRecaptchaSecretKey', 'required|trim');
        }


        if($this->form_validation->run() === TRUE) {
            /** Global page settings */
            $data['pageTitle'] = $this->input->post('settingsPageTitle');
            $data['pageDescription'] = ($this->input->post('settingsPageDescription') == null) ? null : $this->input->post('settingsPageDescription');
            $data['pageTags'] = ($this->input->post('settingsPageTags') == null) ? null : $this->input->post('settingsPageTags');
            $data['pageCharset'] = $this->input->post('settingsPageCharset');
            $data['pageBreakDescription'] = $this->input->post('settingsPageBreakDescription');
            $pagePreloader = $this->input->post('settingsPagePreloader');
            $pageEnabled = $this->input->post('settingsPageEnabled');
            if((isset($pagePreloader)) && ($pagePreloader != null)) $pagePreloader = true; else $pagePreloader = false;
            $data['pagePreloader'] = $pagePreloader;
            if((isset($pageEnabled)) && ($pageEnabled != null)) $pageEnabled = true; else $pageEnabled = false;
            $data['pageActive'] = $pageEnabled;

            /** Voucher settings */
            $data['voucherPrfx'] = $this->input->post('settingsVoucherPrfx');
            $data['voucherLength'] = $this->input->post('settingsVoucherLength');
            
            /** Page layout settings */
            $data['pageBackground'] = ($this->input->post('settingsPageBackground') == null) ? null : $this->input->post('settingsPageBackground');
            $data['pageCustomCSS'] = ($this->input->post('settingsPageCustomCSS') == null) ? null : $this->input->post('settingsPageCustomCSS');
            $data['pageTheme'] = ($this->input->post('settingsPageTheme') == null) ? null : $this->input->post('settingsPageTheme');
            $pageSidebarPos = $this->input->post('settingsSidebarPosition');
            if((isset($pageSidebarPos)) && ($pageSidebarPos != null)) $pageSidebarPos = true; else $pageSidebarPos = false;
            $data['pageSidebarPosition'] = $pageSidebarPos;

            /** Shop Discord Webhook settings */
            $data['shopDiscordWebhookUrl'] = ($this->input->post('settingsWebhookUrl') == null) ? null : $this->input->post('settingsWebhookUrl');
            $data['shopDiscordWebhookEmbedTitle'] = $this->input->post('settingsWebhookEmbedTitle');
            $data['shopDiscordWebhookDesc'] = $this->input->post('settingsWebhookEmbedContent');
            $data['shopDiscordWebhookBotName'] = $this->input->post('settingsWebhookBotName');
            $webhookEmbedHex = str_replace('#', '', $this->input->post('settingsWebhookEmbedColor'));
            $data['shopDiscordWebhookHex'] = $webhookEmbedHex;
            $webhookEnabled = $this->input->post('settingsWebhookEnabled');    
            if((isset($webhookEnabled)) && ($webhookEnabled != null)) $webhookEnabled = true; else $webhookEnabled = false;
            $data['shopDiscordWebhookEnabled'] = $webhookEnabled;
            
            /** Recaptcha Antybot settings */
            $data['recaptchaSiteKey'] = $this->input->post('settingsRecaptchaSiteKey');
            $data['recaptchaSecretKey'] = $this->input->post('settingsRecaptchaSecretKey');


            $config['upload_path'] = './assets/uploads';
            $config['allowed_types'] = 'jpg|png|jpeg|gif';
            $config['max_size'] = 10240;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if($this->upload->do_upload('settingsPageLogo')) {
                $uploadData = $this->upload->data();
                $data['pageLogo'] = base_url('assets/uploads/' . $uploadData['file_name']);
            }
            if($this->upload->do_upload('settingsPageFavicon')) {
                $uploadData = $this->upload->data();
                $data['pageFavicon'] = base_url('assets/uploads/' . $uploadData['file_name']);
            }

            if(!$this->SettingsM->update($data)) {
                $_SESSION['messageDangerSmall'] = "Wystąpił problem podczas łączenia się z bazą danych!";
                redirect(base_url('panel/settings'));
            }

            unset($data);

            $data['user'] = $_SESSION['name'];
            $data['section'] = "Ustawienia";
            $data['details'] = "Użytkownik zmodyfikował ustawienia strony";
            $data['logIP'] = getenv('HTTP_CLIENT_IP') ? : getenv('HTTP_X_FORWARDED_FOR') ? : getenv('HTTP_X_FORWARDED') ? : getenv('HTTP_FORWARDED_FOR') ? : getenv('HTTP_FORWARDED') ? : getenv('REMOTE_ADDR');
            if($data['logIP'] == "::1") $data['logIP'] = "127.0.0.1";
            $data['time'] = time();

            $this->LogsM->add($data);

            $_SESSION['messageSuccessSmall'] = "Ustawienia zostały pomyślnie zapisane!";
            redirect(base_url('panel/settings'));
        } else {
            $_SESSION['messageDangerSmall'] = "Proszę wypełnić wszystkie pola formularza!";
            redirect(base_url('panel/settings'));
        }
    }
}