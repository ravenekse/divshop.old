<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
	public function __construct() {
		parent::__construct();
    }
    
    public function index($pageTitle = null) {

        $settings = $this->SettingsM->getSettings();

		if(!$this->session->userdata('logged') && $settings['pageActive'] == 0) {

			/**  Head section  */
			$headerD['settings'] = $this->SettingsM->getSettings();
			$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Strona wyłączona";
			$this->load->view('components/Header', $headerD);

			/**  Body section  */
			$bodyD['pageBreak'] = $this->SettingsM->getSettings();
			$bodyD['pageBreak'] = array(
                'title' => $bodyD['pageBreak']['pageBreakTitle'],
                'description' => $bodyD['pageBreak']['pageBreakDescription']
            );
			$this->load->view('components/PageBreak', $bodyD);

		} else {

            $this->load->model('ModulesM');
            $modules = $this->ModulesM->getAll();
            
            if($modules[6]['moduleName'] == "pages" && $modules[6]['moduleEnabled'] == 1) {

                if($pageTitle == null) {
                    $this->load->view('errors/html/error_404.php');
                    return;
                } else {

                    require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
                    $this->load->model('NewsM');
                    $this->load->model('PagesM');
                    $api = new DIVShopAPI();
                    $bodyD['divsAPI'] = array(
                        'divsVersion'      =>   $api->get_current_version()
                    );
                    $bodyD['modules'] = $this->ModulesM->getAll();
                    $bodyD['news'] = $this->NewsM->getAll();
                    $bodyD['pages'] = $this->PagesM->getAll();
                    
                    if(!$bodyD['page'] = $this->PagesM->getBy('title', str_replace('-', ' ', $pageTitle))) {
                        $this->load->view('errors/html/error_404.php');
                        log_message('error', "[Controller: " . ucfirst($this->uri->segment(1)) . ".php | Line: " . (__LINE__ - 2) . "] Page with name '" . $pageTitle . "' does not exist in database!");
                        return;
                    }
                    
                    if($bodyD['page']['pageActive'] == 0 || $bodyD['page']['link'] != null) {
                        $this->load->view('errors/html/error_404.php');
                        return;
                    } else {
                    
                        /**  Head section  */
                        $headerD['settings'] = $this->SettingsM->getSettings();
                        $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                        $headerD['pageSubtitle'] = $bodyD['page']['title'];
                        $this->load->view('components/Header', $headerD);

                        /**  Body section  */
                        $this->load->view('Page', $bodyD);

                    }
                }
            } else {
                $this->load->view('errors/html/error_404.php');
                return;
            }
        }
    }
}