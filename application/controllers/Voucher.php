<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $settings = $this->SettingsM->getSettings();

        if (!$this->session->userdata('logged') && $settings['pageActive'] == 0) {

            /**  Head section  */
            $headerD['settings'] = $this->SettingsM->getSettings();
            $headerD['pageTitle'] = $headerD['settings']['pageTitle'].' | Strona wyłączona';
            $this->load->view('components/Header', $headerD);

            /**  Body section  */
            $bodyD['pageBreak'] = $this->SettingsM->getSettings();
            $bodyD['pageBreak'] = [
                'title'       => $bodyD['pageBreak']['pageBreakTitle'],
                'description' => $bodyD['pageBreak']['pageBreakDescription'],
            ];
            $this->load->view('components/PageBreak', $bodyD);
        } else {
            $this->load->model('ModulesM');
            $modules = $this->ModulesM->getAll();

            if ($modules[4]['moduleName'] == 'vouchers' && $modules[4]['moduleEnabled'] == 1) {
                require_once APPPATH.'libraries/divshop-api/divsAPI.php';
                $this->load->model('ModulesM');
                $this->load->model('NewsM');
                $this->load->model('VouchersM');
                $this->load->model('PagesM');
                $api = new DIVShopAPI();
                $bodyD['divsAPI'] = [
                    'divsVersion'  => $api->get_current_version(),
                ];

                /**  Head section  */
                $headerD['settings'] = $this->SettingsM->getSettings();
                $headerD['pageTitle'] = $headerD['settings']['pageTitle'];
                $headerD['pageSubtitle'] = 'Realizacja Vouchera';
                $this->load->view('components/Header', $headerD);

                /**  Body section  */
                $bodyD['modules'] = $this->ModulesM->getAll();
                $bodyD['news'] = $this->NewsM->getAll();
                $bodyD['pages'] = $this->PagesM->getAll();

                $this->load->view('Voucher', $bodyD);
            } else {
                $this->load->view('errors/html/error_404.php');

                return;
            }
        }
    }
}
