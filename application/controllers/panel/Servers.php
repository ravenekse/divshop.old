<?php
/**
 * @author   DIVShop Team
 * @copyright   Copyright (c) 2021 DIVShop.pro (https://divshop.pro/)
 *
 * @link   https://divshop.pro
 **/
defined('BASEPATH') or exit('No direct script access allowed');

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

class Servers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged')) {
            redirect($this->config->base_url('admin/auth'));
        }
    }

    public function index()
    {
        require_once APPPATH.'libraries/minecraft-lib/MinecraftPing.php';
        require_once APPPATH.'libraries/minecraft-lib/MinecraftPingException.php';
        require_once APPPATH.'libraries/divshop-api/divsAPI.php';
        $this->load->model('ServersM');
        $this->load->model('ModulesM');
        $api = new DIVShopAPI();
        $bodyD['divsAPI'] = [
            'divsVersion'      => $api->get_current_version(),
            'divsUpdate'       => $api->check_update(),
        ];

        /**  Head section  */
        $headerD['settings'] = $this->SettingsM->getSettings();
        $headerD['pageTitle'] = $headerD['settings']['pageTitle'].' | Serwery';
        $this->load->view('components/Header', $headerD);

        /**  Body Section  */
        $servers = $this->ServersM->getAll();
        $bodyD['modules'] = $this->ModulesM->getAll();
        $bodyD['servers'] = [];
        foreach ($servers as $server) {
            try {
                $query = new MinecraftPing($server['ip'], $server['port']);
                $result = $query->Query();
                $version = $result['version']['name'];
                $version = str_split($version);
                $server['status']['version'] = '';
                foreach ($version as $char) {
                    if ((is_numeric($char)) || ($char == '.')) {
                        $server['status']['version'] .= $char;
                    }
                }
                $server['status']['onlinePlayers'] = $result['players']['online'];
                $server['status']['maxPlayers'] = $result['players']['max'];
            } catch (MinecraftPingException $e) {
                log_message('error', '[Controller: '.ucfirst($this->uri->segment(1)).'.php | Line: '.(__LINE__ - 1).'] MinecraftPingException: '.$e->getMessage());
            } finally {
                if (isset($query)) {
                    $query->Close();
                }
            }
            array_push($bodyD['servers'], $server);
        }

        $this->load->view('panel/Servers', $bodyD);
    }
}
