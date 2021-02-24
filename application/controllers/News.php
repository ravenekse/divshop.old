<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {
	public function __construct() {
		parent::__construct();
    }
    
    public function index($newsId = null, $newsTitle = null) {

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
            
            if($modules[0]['moduleName'] == "news" && $modules[0]['moduleEnabled'] == 1) {

                if($newsId == null && $newsTitle == null) {
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
                    $bodyD['pages'] = $this->PagesM->getAll();

                    if(!$bodyD['news'] = $this->NewsM->getBy('id', $newsId)) {
                        $this->load->view('errors/html/error_404.php');
                        return;
                    }
                    
                    if(getNewsUrl($bodyD['news']['title']) != $newsTitle) {
                        $this->load->view('errors/html/error_404.php');
                        return;
                    } else {

                        if($bodyD['news']['newsActive'] == 1) {
                    
                            /**  Head section  */
                            $headerD['settings'] = $this->SettingsM->getSettings();
                            $headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | News";
                            $headerD['pageSubtitle'] = $bodyD['news']['title'];
                            $this->load->view('components/Header', $headerD);

                            /**  Body section  */
                            $this->load->view('News', $bodyD);
                        } else {
                            $this->load->view('errors/html/error_404.php');
                            return;
                        }

                    }
                }
            } else {
                $this->load->view('errors/html/error_404.php');
                return;
            }
        }
    }
}