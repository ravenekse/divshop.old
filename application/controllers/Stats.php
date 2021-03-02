<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {
	public function __construct() {
		parent::__construct();
    }

    public function index($page = 1) {

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

            require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
            $this->load->model('ModulesM');
            $this->load->model('NewsM');
            $this->load->model('PagesM');
            $this->load->model('StatsM');
            $api = new DIVShopAPI();
            $bodyD['divsAPI'] = array(
                'divsVersion'  =>  $api->get_current_version()
            );
            $bodyD['modules'] = $this->ModulesM->getAll();

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
            $headerD['pageSubtitle'] = "Statystyki";
            $this->load->view('components/Header', $headerD);

            /**  Body section  */
            $bodyD['news'] = $this->NewsM->getAll();
            $bodyD['pages'] = $this->PagesM->getAll();

            if($page == 1) {
                $start = 0;
            } else {
                $start = ($page - 1) * 15;
            }

            $playerStats = $this->StatsM->getAllPlayers();
            $bodyD['playerStats'] = $this->StatsM->getAllPlayers($start, 15);

            if(count($playerStats) != 0) {
                if(count($playerStats) >= 1) {
                    for($i = 1; $i <= 1; $i++) {
                        $bodyD['playerPos'] = $start + $i;
                    }
                } 
            }

            $config['base_url'] = base_url('stats');
            $config['total_rows'] = count($playerStats);
            $config['per_page'] = 15;
            $config['uri_segment'] = 2;
            $config['num_links'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['attributes'] = array('class' => 'page-link');
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;
            $config['full_tag_open'] = '<nav class="mt-5"><ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul></nav>';
            $config['prev_link'] = '<i class="fa fa-chevron-left"></i>';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-chevron-right"></i>';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $bodyD['pagination'] = $this->pagination->create_links();

            $this->load->view('Stats', $bodyD);

        }
    }
}