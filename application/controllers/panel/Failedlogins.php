<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 * @link   https://divshop.pro
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Failedlogins extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged')) redirect($this->config->base_url('admin/auth'));
    }

    public function index($page = 1) {

        require_once(APPPATH . 'libraries/divshop-api/divsAPI.php');
        $this->load->model('ModulesM');
        $this->load->model('FailedLoginsM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = array(
            'divsVersion'      =>   $api->get_current_version(),
            'divsUpdate'       =>   $api->check_update()
        );

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
		$headerD['pageTitle'] = $headerD['settings']['pageTitle'] . " | Nieudane próby logowania";
        $this->load->view('components/Header', $headerD);
        
        /** Body section */
        $failedlogins = $this->FailedLoginsM->getAllLogs();
        $bodyD['modules'] = $this->ModulesM->getAll();

        if($page == 1) {
            $start = 0;
        } else {
            $start = ($page - 1) * 15;
        }
        $bodyD['failedlogins'] = $this->FailedLoginsM->getAllLogs($start, 15);
        $config['base_url'] = base_url('panel/failedlogins');
        $config['total_rows'] = count($failedlogins);
        $config['per_page'] = 15;
        $config['uri_segment'] = 3;
        $config['num_links'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['attributes'] = array('class' => 'page-link');
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['full_tag_open'] = '<nav class="d-flex justify-content-center mt-3"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['prev_link'] = '<span aria-hidden="true">«</span>';
        $config['prev_tag_open'] = '<li class="page-item item-rounded">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '<span aria-hidden="true">»</span>';
        $config['next_tag_open'] = '<li class="page-item item-rounded">';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active item-rounded"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item item-rounded">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $bodyD['pagination'] = $this->pagination->create_links();
        $this->load->view('panel/FailedLogins', $bodyD);
    }
}